<?php

namespace App\Http\Controllers;

use App\Exports\ExportStock;
use App\Exports\ExportReports;
use App\Garden;
use App\Grade;
use App\Owner;
use App\Package;
use App\Imports\StockImport;
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

    // Initialize default values for all possible indexes
    $warehouseIds = Stock::distinct('warehouse_id')->pluck('warehouse_id');
    foreach ($warehouseIds as $warehouseId) {
        if (!isset($bagsPerBay[$warehouseId])) {
            $bagsPerBay[$warehouseId] = 0;
        }
    }

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
    

    
    
    public function calculateTotalMismatchQty($importedData)
    {
        $rows = $importedData->rows;

        if (!is_iterable($rows)) {
            return 0;
        }

        $totalMismatchQty = 0;

        foreach ($rows as $row) {
            $qty = $row->qty;
            $gardenName = $row->garden;
            $invoice = $row->invoice;
            $gradeName = $row->grade;
            $packageType = $row->package;

            $garden = Garden::where('name', $gardenName)->first();
            $grade = Grade::where('name', $gradeName)->first();
            $package = Package::where('name', $packageType)->first();

            if (!$garden || !$grade || !$package) {
                continue;
            }

            $invoicedQty = Stock::where('garden_id', $garden->id)
                ->where('invoice', $invoice)
                ->where('grade_id', $grade->id)
                ->where('package_id', $package->id)
                ->sum('qty');

            if ($qty != $invoicedQty) {
                $totalMismatchQty += abs($qty - $invoicedQty);
            }
        }

        return $totalMismatchQty;
    }

    public function apiImport(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|mimes:xlsx,xls',
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = $file->getClientOriginalName();
            Excel::import(new StockImport(), $file);

            $importedData = new ImportedData();
            $importedData->file_name = $fileName;
            $importedData->save();

            $totalMismatchQty = $this->calculateTotalMismatchQty($importedData);

            return $this->index($totalMismatchQty);
        }

        return redirect()->back()->with(['error' => 'No file uploaded.']);
    }

    public function index()
    {
        $totalBags = $this->countTotalBags();
        $bagsPerWarehouse = $this->calculateBagsPerWarehouse();
        $bagsPerBay = $this->calculateBagsPerBay();
        $ownersCountPerWarehouse = $this->getFarmOwnersCountPerWarehouse();

        $importedData = new ImportedData();
        [$mismatches, $totalMismatchQty] = $this->fetchMismatches($importedData);

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
            'mismatches',
            'totalMismatchQty',
            'monthlyReports',
            'filter'
        ));
    }

    private function fetchMismatches($importedData)
    {
        $rows = $importedData->rows;

        if (!is_iterable($rows)) {
            return [[], 0]; // Return empty arrays if $rows is not iterable
        }

        $mismatches = [];
        $totalMismatchQty = 0;

        foreach ($rows as $row) {
            $gardenName = $row->garden;
            $invoice = $row->invoice;
            $qty = $row->qty;
            $gradeName = $row->grade;
            $packageType = $row->package;

            // Get the garden, grade, and package by their names
            $garden = Garden::where('name', $gardenName)->first();
            $grade = Grade::where('name', $gradeName)->first();
            $package = Package::where('name', $packageType)->first();

            // If any of the required data is missing, skip the row
            if (!$garden || !$grade || !$package) {
                continue;
            }

            // Compare the imported data with the invoiced stock
            $invoicedQty = Stock::where('garden_id', $garden->id)
                ->where('invoice', $invoice)
                ->where('grade_id', $grade->id)
                ->where('package_id', $package->id)
                ->sum('qty');

            if ($qty != $invoicedQty) {
                $mismatches[] = [
                    'garden' => $gardenName,
                    'invoice' => $invoice,
                    'qty' => $qty,
                    'grade' => $gradeName,
                    'package' => $packageType,
                ];
                $totalMismatchQty += abs($qty - $invoicedQty);
            }
        }

        return [$mismatches, $totalMismatchQty];
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

    public function exportToExcel($warehouse_id)
    {
        $monthlyReports = $this->generateMonthlyReports($warehouse_id);

        $data = [];

        // Prepare the data for export
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

        // Generate the Excel file using Maatwebsite\Excel package
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
}
