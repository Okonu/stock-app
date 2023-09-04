<?php

namespace App\Http\Controllers;

use App\Exports\ExportReports;
use App\Exports\ExportStock;
use App\Exports\ReconcileStockExport;
use App\Garden;
use App\Grade;
use App\Owner;
use App\Package;
use App\Stock;
use App\Warehouse;
use App\WarehouseBay;
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
        $this->middleware('role:admin,staff');
    }

    public function countTotalBags()
    {
        $totalBags = Stock::sum('qty');

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

        // Get distinct months for the given warehouse
        $distinctMonths = DB::table('stocks')
            ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month')
            ->where('warehouse_id', $warehouse_id)
            ->distinct()
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        foreach ($distinctMonths as $month) {
            $monthName = Carbon::createFromDate($month->year, $month->month)->format('F');

            // Get the monthly data for the given warehouse, farms, and bags count
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
        // extractSysInvoice
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

        // fetch data from legacies table
        $legaciesQuery = 'SELECT invoice, qty, garden, grade FROM legacies';
        $legaciesResult = DB::select($legaciesQuery);

        // fetch data from stocks table
        $stocksQuery = 'SELECT s.invoice AS stock_invoice, s.qty AS stock_qty, g.name AS garden_name, gd.name AS grade_name
                        FROM stocks s
                        JOIN gardens g ON s.garden_id = g.id
                        JOIN grades gd ON s.grade_id = gd.id';
        $stocksResult = DB::select($stocksQuery);

        // store the legacies data
        $legaciesData = [];
        foreach ($legaciesResult as $row) {
            $legaciesData[] = (array) $row;
        }

        // store the stocks data
        $stocksData = [];
        foreach ($stocksResult as $row) {
            $stocksData[] = (array) $row;
        }

        // Group the legacies data by sys invoice, garden, and grade
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

        // Group the stocks data by sys invoice, garden, and grade
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

        // Perform matching and prepare data for the DataTable
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

        // Iterate over $groupedStocksData and add unmatched entries to $matchedData
        foreach ($groupedStocksData as $stock) {
            $stockInvoice = $stock['stock_invoice'];
            $stockQty = $stock['stock_qty'];
            $stockGarden = $stock['garden_name'];
            $stockGrade = $stock['grade_name'];

            // Check if stock invoice exists in $matchedData
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

        // Calculate totalSysQty and totalPhysQty
        $totalSysQty = array_sum(array_column($legaciesData, 'qty'));
        $totalPhysQty = array_sum(array_column($stocksData, 'stock_qty'));

        // Calculate missingBagsQty
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

        // return view('reconciliation.index', $response);
        $responseJson = json_encode($response);

        return view('reconciliation.index', ['jsonData' => $responseJson, 'data' => $response]);
    }

    public function index()
    {
        $totalBags = $this->countTotalBags();
        $bagsPerWarehouse = $this->calculateBagsPerWarehouse();
        $bagsPerBay = $this->calculateBagsPerBay();
        $ownersCountPerWarehouse = $this->getFarmOwnersCountPerWarehouse();

        $warehouse_id = 'warehouse_id';
        // $monthlyReports = $this->generateMonthlyReports($warehouse_id);
        $monthlyReports = $this->generateMonthlyReports($warehouse_id);

        $warehouse = Warehouse::orderBy('name', 'ASC')->get()->pluck('name', 'id');
        $bays = WarehouseBay::orderBy('name', 'ASC')->get()->pluck('name', 'id');
        $owner = Owner::orderBy('name', 'ASC')->get()->pluck('name', 'id');
        $garden = Garden::orderBy('name', 'ASC')->get()->pluck('name', 'id');
        $grade = Grade::orderBy('name', 'ASC')->get()->pluck('name', 'id');
        $package = Package::orderBy('name', 'ASC')->get()->pluck('name', 'id');
        $stock = Stock::all();
        $warehouse_id = 'warehouse_id';
        $stockDates = Stock::select('warehouse_id')
            ->selectRaw('GROUP_CONCAT(DISTINCT DATE_FORMAT(created_at, "%Y-%m-%d")) as stock_dates')
            ->groupBy('warehouse_id')
            ->pluck('stock_dates', 'warehouse_id')
            ->toArray();
        $filter = request('filter');

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
            'filter'
        ));
    }

    public function create()
    {
        // Create
    }

    public function store(Request $request)
    {
        $warehouse = Warehouse::orderBy('name', 'ASC')
            ->get()
            ->pluck('name', 'id');

        $bay = WarehouseBay::orderBy('name', 'ASC')
            ->get()
            ->pluck('name', 'id');

        $owner = Owner::orderBy('name', 'ASC')
            ->get()
            ->pluck('name', 'id');

        $garden = Garden::orderBy('name', 'ASC')
            ->get()
            ->pluck('name', 'id');

        $grade = Grade::orderBy('name', 'ASC')
            ->get()
            ->pluck('name', 'id');

        $package = Package::orderBy('name', 'ASC')
            ->get()
            ->pluck('name', 'id');

        $this->validate($request, [
            'warehouse_id' => 'required',
            'warehouse_bay_id' => 'required',
            'owner_id' => 'required',
            'garden_id' => 'required',
            'grade_id' => 'required',
            'package_id' => 'required',
            'invoice' => 'required|string',
            'qty' => 'required|integer',
            'year' => 'required|string',
            'remark' => 'required|string',
            // 'file' => 'required|mimes:xlsx,xls',
        ]);
    }

    public function show($id)
    {
        //show
    }

    public function edit($id)
    {
        $stock = Stock::find($id);

        return $stock;
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'warehouse_id' => 'required',
            'warehouse_bay_id' => 'required',
            'owner_id' => 'required',
            'garden_id' => 'required',
            'grade_id' => 'required',
            'package_id' => 'required',
            'invoice' => 'required|string',
            'qty' => 'required|integer',
            'year' => 'required|string',
            'remark' => 'required|string',
        ]);

        $stock = Stock::findOrFail($id);
        $stock->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Stock Updated Successfully',
        ]);
    }

    public function exportToExcel($warehouse_id)
    {
        $monthlyReports = $this->generateMonthlyReports($warehouse_id);

        $data = [];

        foreach ($monthlyReports as $monthlyReport) {
            $month = $monthlyReport['month'];
            $reportData = $monthlyReport['data'];

            foreach ($reportData as $item) {
                $owner = $item->owner->name;
                $garden = $item->garden->name;
                $totalBags = $item->total_bags;

                $data[] = [
                    'Month' => $month,
                    'Owner' => $owner,
                    'Garden' => $garden,
                    'Total Bags' => $totalBags,
                ];
            }
        }

        return Excel::download(new ExportReports($data), 'monthly_reports.xlsx');
    }

    public function apiStocks()
    {
        $stocks = Stock::with(['user', 'warehouse', 'bays', 'owner', 'garden', 'grade', 'package'])
            ->get();

        return Datatables::of($stocks)
            ->addColumn('user_name', function ($stock) {
                return $stock->user ? $stock->user->name : null;
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
                ->addColumn('action', function ($stock) {
                    return $stock->resolved ? 'Resolved' : 'Unresolved';
                })
            ->make(true);
    }

    public function exportStockAll()
    {
        $stock = Stock::all();
        $pdf = \PDF::loadView('stocks.stockAllPDF', compact('stock'));

        return $pdf->download('stock.pdf');
    }

    public function exportStock($id)
    {
        $stock = Stock::findOrFail($id);
        $pdf = \PDF::loadView('stocks.exportStockPDF', compact('stock'));

        return $pdf->download($stock->id.'_stock.pdf');
    }

    public function exportExcel()
    {
        return (new ExportStock())->download('stock.xlsx');
    }

    public function reconcileStockExport()
    {
        $data = $this->reconcileStock()['data'];

        return Excel::download(new ReconcileStockExport($data), 'reconciliation.xlsx');
    }
}
