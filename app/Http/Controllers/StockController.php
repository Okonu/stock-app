<?php

namespace App\Http\Controllers;

use App\Exports\ExportStock;
use App\Exports\ExportReports;
use App\Exports\ReconcileStockExport;
use App\Garden;
use App\Grade;
use App\Owner;
use App\Package;
use App\Stock;
use App\Warehouse;
use App\WarehouseBay;
use Illuminate\Http\Request;
use App\ImportedData;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;


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
    // Logic to extractSysInvoice
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

    // SQL query to fetch data from legacies table
    $legaciesQuery = "SELECT invoice, qty, garden, grade FROM legacies";
    $legaciesResult = DB::select($legaciesQuery);

    // SQL query to fetch data from stocks table
    $stocksQuery = "SELECT s.invoice AS stock_invoice, s.qty AS stock_qty, g.name AS garden_name, gd.name AS grade_name
                    FROM stocks s
                    JOIN gardens g ON s.garden_id = g.id
                    JOIN grades gd ON s.grade_id = gd.id";
    $stocksResult = DB::select($stocksQuery);

    // Create an array to store the legacies data
    $legaciesData = [];
    foreach ($legaciesResult as $row) {
        $legaciesData[] = (array) $row;
    }

    // Create an array to store the stocks data
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

        $key = $sysInvoice . '_' . $garden . '_' . $grade;
        if (!isset($groupedLegaciesData[$key])) {
            $groupedLegaciesData[$key] = [
                'invoice' => $sysInvoice,
                'qty' => $legacy['qty'],
                'garden' => $garden,
                'grade' => $grade
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

        $key = $sysInvoice . '_' . $garden . '_' . $grade;
        if (!isset($groupedStocksData[$key])) {
            $groupedStocksData[$key] = [
                'stock_invoice' => $sysInvoice,
                'stock_qty' => $stock['stock_qty'],
                'garden_name' => $garden,
                'grade_name' => $grade
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
                    'Status' => ($legacyQty == $stockQty) ? 'match' : 'mismatch'
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
                'Status' => 'mismatch'
            ];
        }

        $count++;
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
                'Status' => 'unmatched'
            ];
            $count++;
        }
    }

    // Calculate totalSysQty and totalPhysQty
    $totalSysQty = array_sum(array_column($legaciesData, 'qty'));
    $totalPhysQty = array_sum(array_column($stocksData, 'stock_qty'));

    // Calculate missingBagsQty
    $missingBagsQty = $totalSysQty - $totalPhysQty;

    // Prepare the response data
    $response = [
        'data' => $matchedData,
        'stats' => [
            'totalSysQty' => $totalSysQty,
            'totalPhysQty' => $totalPhysQty,
            'totalMismatchInvoices' => count(array_filter($matchedData, function ($item) {
                return $item['Status'] == 'mismatch';
            })),
            'missingBagsQty' => $missingBagsQty
        ]
    ];

    // return view('reconciliation.index', $response);
    $responseJson = json_encode($response);
    return view('reconciliation.index', ['jsonData' => $responseJson, 'data' => $response]);
}

    // public function index(Request $request)
public function index(Request $request)
{
    // Calculate various statistics
    $totalBags = $this->countTotalBags();
    $bagsPerWarehouse = $this->calculateBagsPerWarehouse();
    $bagsPerBay = $this->calculateBagsPerBay();
    $ownersCountPerWarehouse = $this->getFarmOwnersCountPerWarehouse();

    // Get the selected month for filtering
    $selectedMonth = $request->input('selectedMonth');

    // Define the warehouse_id variable
    $warehouse_id = 'warehouse_id';

    // Build the query to retrieve stock data
    $stocksQuery = Stock::query()
        ->with(['user', 'warehouse', 'owner', 'garden', 'grade', 'package'])
        ->select([
            'stocks.*', // Select all columns from stocks
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

    // Check if a specific month is selected for filtering
   if ($selectedMonth) {
    $stocksQuery->whereMonth('stocks.created_at', $selectedMonth);
}
    // Retrieve paginated stock data
    $stocks = $stocksQuery->orderBy('stocks.created_at', 'desc')->paginate(10);

    // Get data for dropdown filters
    $warehouse = Warehouse::orderBy('name', 'ASC')->get()->pluck('name', 'id');
    $bays = WarehouseBay::orderBy('name', 'ASC')->get()->pluck('name', 'id');
    $owner = Owner::orderBy('name', 'ASC')->get()->pluck('name', 'id');
    $garden = Garden::orderBy('name', 'ASC')->get()->pluck('name', 'id');
    $grade = Grade::orderBy('name', 'ASC')->get()->pluck('name', 'id');
    $package = Package::orderBy('name', 'ASC')->get()->pluck('name', 'id');

    // Get all stock data for filtering
    $stock = Stock::all();

    // Retrieve stock dates for the filter dropdown
    $stockDates = Stock::select('warehouse_id')
        ->selectRaw('GROUP_CONCAT(DISTINCT DATE_FORMAT(created_at, "%Y-%m-%d")) as stock_dates')
        ->groupBy('warehouse_id')
        ->pluck('stock_dates', 'warehouse_id')
        ->toArray();

    // Retrieve monthly reports based on warehouse_id
    $monthlyReports = $this->generateMonthlyReports($warehouse_id);

    // Get the filter value
    $filter = $request->input('filter');

    // Render the view with the data
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
        'stocks' // Add the paginated stock data to the view
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
    
    public function apiStocks(Request $request)
    {
        $selectedMonth = $request->input('month');
    
        // Define the base query with eager loading
        $query = Stock::with(['user', 'warehouse', 'bays', 'owner', 'garden', 'grade', 'package']);
    
        // Add filtering by selected month if it's provided
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
                $viewButton = '<button class="btn btn-info btn-xs view-stock" data-id="' . $stock->id . '">View</button>';
                return $viewButton;
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

    // public function exportExcel()
    // {
    //     return (new ExportStock())->download('stock.xlsx');
    // }
    
public function exportExcel(Request $request)
{
    // Get the selected month and year from the request
    $selectedMonthNumeric = $request->input('month');
    $selectedYear = $request->input('year');

    $selectedMonth = date("F", mktime(0, 0, 0, $selectedMonthNumeric, 1));

    // Query the data for export, including the "Date Taken" column
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

    // Generate the Excel file with the filtered data
    return Excel::download(new ExportStock($query), 'stock_' . $selectedYear . '_' . $selectedMonth . '_data.xlsx');
}


  
    public function reconcileStockExport()
    {
        $data = $this->reconcileStock()['data'];
    
        return Excel::download(new ReconcileStockExport($data), 'reconciliation.xlsx');
    }
}
