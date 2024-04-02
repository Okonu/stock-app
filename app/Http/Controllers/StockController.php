<?php

namespace App\Http\Controllers;

use App\Exports\ExportStock;
use App\Exports\ReconcileStockExport;
use App\Models\Garden;
use App\Models\Grade;
use App\Models\Owner;
use App\Models\Package;
use App\Models\Stock;
use App\Models\Warehouse;
use App\Models\WarehouseBay;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

class StockController extends Controller
{
    public function __construct()
    {
        // $this->middleware('role:admin,staff');
    }

    public function countTotalBags()
    {
        $totalBags = Stock::whereMonth('created_at', '=', now()->month)->sum('qty');

        return $totalBags;
    }

    public function calculateBagsPerWarehouse()
    {
        $bagsPerWarehouse = Stock::select('warehouse_id', DB::raw('SUM(qty) as total_qty'))
            ->groupBy('warehouse_id')
            ->pluck('total_qty', 'warehouse_id');

        return $bagsPerWarehouse;
    }

    private function calculateBagsPerBay()
    {
        $bagsPerBay = [];

        $bays = WarehouseBay::all();

        foreach ($bays as $bay) {
            $bagsCount = Stock::where('warehouse_bay_id', $bay->id)->sum('qty');
            $bagsPerBay[$bay->id] = $bagsCount;
        }
        // dd($bagsPerBay);
        return $bagsPerBay;
    }

    public function getFarmOwnersCountPerWarehouse()
    {
        $ownersCountPerWarehouse = Stock::select('warehouse_id')
            ->selectRaw('COUNT(DISTINCT stocks.owner_id) as owners_count')
            ->selectRaw('GROUP_CONCAT(DISTINCT owners.name) as owners')
            ->selectRaw('GROUP_CONCAT(DISTINCT gardens.name) as gardens')
            ->groupBy('warehouse_id')
            ->leftJoin('owners', 'stocks.owner_id', '=', 'owners.id')
            ->leftJoin('gardens', 'stocks.garden_id', '=', 'gardens.id')
            ->get();

        $bagsPerBay = $this->calculateBagsPerBay();

        $stockDates = Stock::select('warehouse_id')
            ->selectRaw('GROUP_CONCAT(DISTINCT DATE_FORMAT(created_at, "%Y-%m-%d")) as stock_dates')
            ->groupBy('warehouse_id')
            ->pluck('stock_dates', 'warehouse_id')
            ->toArray();

        return $ownersCountPerWarehouse;
    }

    public function generateMonthlyReports()
    {
        $warehouse_id = request()->input('warehouse_id');
        $monthlyReports = new Collection();

        $distinctMonths = DB::table('stocks')
            ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month')
            ->where('warehouse_id', $warehouse_id)
            ->distinct()
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        foreach ($distinctMonths as $month) {
            $monthName = Carbon::createFromDate($month->year, $month->month)->format('F');

            $monthlyData = DB::table('stocks')
                ->select('owner_id', 'garden_id', DB::raw('SUM(qty) as total_bags'))
                ->where('warehouse_id', $warehouse_id)
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->groupBy('owner_id', 'garden_id')
                ->get();

            $monthlyReports->push([
                'month' => $monthName,
                'data' => $monthlyData,
            ]);
        }

        return $monthlyReports;
    }
    public function reconcileStock()
    {
        function extractSysInvoice($sysInvoice)
        {
            if (strpos($sysInvoice, '.') !== false) {
                return strtok($sysInvoice, '.');
            } elseif (strpos($sysInvoice, '/') !== false) {
                return strtok($sysInvoice, '/');
            } elseif (strpos($sysInvoice, '-') !== false) {
                return strtok($sysInvoice, '-');
            } else {
                return $sysInvoice;
            }
        }

        $legaciesQuery = 'SELECT invoice, qty, garden, grade FROM legacies';
        $legaciesResult = DB::select($legaciesQuery);

        $stocksQuery = 'SELECT s.invoice AS stock_invoice, s.qty AS stock_qty, g.name AS garden_name, gd.name AS grade_name
                        FROM stocks s
                        JOIN gardens g ON s.garden_id = g.id
                        JOIN grades gd ON s.grade_id = gd.id';
        $stocksResult = DB::select($stocksQuery);

        $legaciesData = [];
        foreach ($legaciesResult as $row) {
            $legaciesData[] = (array) $row;
        }

        $stocksData = [];
        foreach ($stocksResult as $row) {
            $stocksData[] = (array) $row;
        }

        $groupedLegaciesData = [];
        foreach ($legaciesData as $legacy) {
            $sysInvoice = extractSysInvoice($legacy['invoice']);
            $garden = $legacy['garden'];
            $grade = $legacy['grade'];

            $key = $sysInvoice.'_'.$garden.'_'.$grade;
            if (!isset($groupedLegaciesData[$key])) {
                $groupedLegaciesData[$key] = [
                    'invoice' => $sysInvoice,
                    'qty' => $legacy['qty'],
                    'garden' => $garden,
                    'grade' => $grade,
                ];
            } else {
                $groupedLegaciesData[$key]['qty'] += $legacy['qty'];
            }
        }

        $groupedStocksData = [];
        foreach ($stocksData as $stock) {
            $sysInvoice = extractSysInvoice($stock['stock_invoice']);
            $garden = $stock['garden_name'];
            $grade = $stock['grade_name'];

            $key = $sysInvoice.'_'.$garden.'_'.$grade;
            if (!isset($groupedStocksData[$key])) {
                $groupedStocksData[$key] = [
                    'stock_invoice' => $sysInvoice,
                    'stock_qty' => $stock['stock_qty'],
                    'garden_name' => $garden,
                    'grade_name' => $grade,
                ];
            } else {
                $groupedStocksData[$key]['stock_qty'] += $stock['stock_qty'];
            }
        }

        $matchedData = [];
        $count = 1;
        foreach ($groupedLegaciesData as $legacy) {
            $legacyInvoice = $legacy['invoice'];
            $legacyQty = $legacy['qty'];
            $legacyGarden = $legacy['garden'];
            $legacyGrade = $legacy['grade'];

            $matched = false;

            foreach ($groupedStocksData as $stock) {
                $stockInvoice = $stock['stock_invoice'];
                $stockQty = $stock['stock_qty'];
                $stockGarden = $stock['garden_name'];
                $stockGrade = $stock['grade_name'];

                $sysInvoice = extractSysInvoice($legacyInvoice);

                if ($sysInvoice == $stockInvoice && $legacyGarden == $stockGarden && $legacyGrade == $stockGrade) {
                    $matchedData[] = [
                        '#' => $count,
                        'sys' => $legacyInvoice,
                        'phys' => $stockInvoice,
                        'sys_Qty' => $legacyQty,
                        'phys_Qty' => $stockQty,
                        'Garden' => $stockGarden,
                        'Grade' => $stockGrade,
                        'Status' => ($legacyQty == $stockQty) ? 'match' : 'mismatch',
                    ];

                    $matched = true;
                }
            }

            if (!$matched) {
                $matchedData[] = [
                    '#' => $count,
                    'sys' => $legacyInvoice,
                    'phys' => '',
                    'sys_Qty' => $legacyQty,
                    'phys_Qty' => 0,
                    'Garden' => $legacyGarden,
                    'Grade' => $legacyGrade,
                    'Status' => 'mismatch',
                ];
            }

            ++$count;
        }

        foreach ($groupedStocksData as $stock) {
            $stockInvoice = $stock['stock_invoice'];
            $stockQty = $stock['stock_qty'];
            $stockGarden = $stock['garden_name'];
            $stockGrade = $stock['grade_name'];

            if (!in_array($stockInvoice, array_column($matchedData, 'phys'))) {
                $matchedData[] = [
                    '#' => $count,
                    'sys' => '',
                    'phys' => $stockInvoice,
                    'sys_Qty' => 0,
                    'phys_Qty' => $stockQty,
                    'Garden' => $stockGarden,
                    'Grade' => $stockGrade,
                    'Status' => 'unmatched',
                ];
                ++$count;
            }
        }

        $totalSysQty = array_sum(array_column($legaciesData, 'qty'));
        $totalPhysQty = array_sum(array_column($stocksData, 'stock_qty'));

        $missingBagsQty = $totalSysQty - $totalPhysQty;

        $response = [
            'data' => $matchedData,
            'stats' => [
                'totalSysQty' => $totalSysQty,
                'totalPhysQty' => $totalPhysQty,
                'totalMismatchInvoices' => count(array_filter($matchedData, function ($item) {
                    return $item['Status'] == 'mismatch';
                })),
                'missingBagsQty' => $missingBagsQty,
            ],
        ];

        $responseJson = json_encode($response);

        return view('reconciliation.index', ['jsonData' => $responseJson, 'data' => $response]);
    }

    public function index(Request $request)
    {
        $totalBags = $this->countTotalBags();
        $bagsPerWarehouse = $this->calculateBagsPerWarehouse();
        $bagsPerBay = $this->calculateBagsPerBay();
        $ownersCountPerWarehouse = $this->getFarmOwnersCountPerWarehouse();

        $selectedMonth = $request->input('selectedMonth');

        $warehouse_id = 'warehouse_id';

        $stocksQuery = Stock::query()
            ->with(['user', 'warehouse', 'owner', 'garden', 'grade', 'package'])
            ->select([
                'stocks.*',
                'users.name as user_name',
                'warehouses.name as warehouse_name',
                'owners.name as owner_name',
                'gardens.name as garden_name',
                'grades.name as grade_name',
                'packages.name as package_name',
            ])
            ->leftJoin('users', 'stocks.user_id', '=', 'users.id')
            ->leftJoin('warehouses', 'stocks.warehouse_id', '=', 'warehouses.id')
            ->leftJoin('owners', 'stocks.owner_id', '=', 'owners.id')
            ->leftJoin('gardens', 'stocks.garden_id', '=', 'gardens.id')
            ->leftJoin('grades', 'stocks.grade_id', '=', 'grades.id')
            ->leftJoin('packages', 'stocks.package_id', '=', 'packages.id');

        if ($selectedMonth) {
            $stocksQuery->whereMonth('stocks.created_at', $selectedMonth);
        }

        $stocks = $stocksQuery->orderBy('stocks.created_at', 'desc')->paginate(10);

        $warehouse = Warehouse::orderBy('name', 'ASC')->get()->pluck('name', 'id');
        $bays = WarehouseBay::orderBy('name', 'ASC')->get()->pluck('name', 'id');
        $owner = Owner::orderBy('name', 'ASC')->get()->pluck('name', 'id');
        $garden = Garden::orderBy('name', 'ASC')->get()->pluck('name', 'id');
        $grade = Grade::orderBy('name', 'ASC')->get()->pluck('name', 'id');
        $package = Package::orderBy('name', 'ASC')->get()->pluck('name', 'id');

        $stock = Stock::all();

        $stockDates = Stock::select('warehouse_id')
            ->selectRaw('GROUP_CONCAT(DISTINCT DATE_FORMAT(created_at, "%Y-%m-%d")) as stock_dates')
            ->groupBy('warehouse_id')
            ->pluck('stock_dates', 'warehouse_id')
            ->toArray();

        $monthlyReports = $this->generateMonthlyReports($warehouse_id);

        $filter = $request->input('filter');

        return view('stocks.index', compact(
            'warehouse',
            'warehouse_id',
            'bays',
            'owner',
            'garden',
            'grade',
            'package',
            'totalBags',
            'bagsPerWarehouse',
            'ownersCountPerWarehouse',
            'bagsPerBay',
            'stockDates',
            'monthlyReports',
            'filter',
            'selectedMonth',
            'stocks'
        ));
    }
    public function apiStocks(Request $request)
    {
        $selectedMonth = $request->input('month');

        $query = Stock::with(['user', 'warehouse', 'bays', 'owner', 'garden', 'grade', 'package']);

        if (!empty($selectedMonth)) {
            $query->whereMonth('created_at', '=', $selectedMonth);
        }

        $stocks = $query->get();

        return Datatables::of($stocks)
            ->addColumn('user_name', function ($stock) {
                return $stock->user ? $stock->user->name : null;
            })
             ->addColumn('created_at', function ($stock) {
                 return $stock->created_at->format('Y-m-d');
             })
            ->addColumn('warehouse_name', function ($stock) {
                return $stock->warehouse ? $stock->warehouse->name : null;
            })
            ->addColumn('warehouse_bay_name', function ($stock) {
                return $stock->bays ? $stock->bays->name : null;
            })
            ->addColumn('owner_name', function ($stock) {
                return $stock->owner ? $stock->owner->name : null;
            })
            ->addColumn('garden_name', function ($stock) {
                return $stock->garden ? $stock->garden->name : null;
            })
            ->addColumn('grade_name', function ($stock) {
                return $stock->grade ? $stock->grade->name : null;
            })
            ->addColumn('package_name', function ($stock) {
                return $stock->package ? $stock->package->name : null;
            })
            ->addColumn('actions', function ($stock) {
                $viewButton = '<button class="btn btn-info btn-xs view-stock" data-id="'.$stock->id.'">View</button>';

                return $viewButton;
            })
            ->make(true);
    }

    public function exportStockAll()
    {
        $stock = Stock::all();
        $pdf = PDF::loadView('stocks.stockAllPDF', compact('stock'));

        return $pdf->download('stock.pdf');
    }

    public function exportStock($id)
    {
        $stock = Stock::findOrFail($id);
        $pdf = PDF::loadView('stocks.exportStockPDF', compact('stock'));

        return $pdf->download($stock->id.'_stock.pdf');
    }

    public function exportExcel(Request $request)
    {
        $selectedMonthNumeric = $request->input('month');
        $selectedYear = $request->input('year');

        $selectedMonth = date('F', mktime(0, 0, 0, $selectedMonthNumeric, 1));

        $query = Stock::query()
            ->with(['user', 'warehouse', 'bays', 'owner', 'garden', 'grade', 'package'])
            ->select('stocks.*', 'users.name as user_name')
            ->leftJoin('users', 'stocks.user_id', '=', 'users.id');

        if (!empty($selectedMonthNumeric)) {
            $query->whereMonth('stocks.created_at', '=', $selectedMonthNumeric);
        }

        if (!empty($selectedYear)) {
            $query->whereYear('stocks.created_at', '=', $selectedYear);
        }

        $data = $query->get();

        return Excel::download(new ExportStock($query), 'stock_'.$selectedYear.'_'.$selectedMonth.'_data.xlsx');
    }

    public function reconcileStockExport()
    {
        $data = $this->reconcileStock()['data'];

        return Excel::download(new ReconcileStockExport($data), 'reconciliation.xlsx');
    }
}
