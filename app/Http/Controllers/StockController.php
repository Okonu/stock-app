<?php

namespace App\Http\Controllers;

use App\Exports\ExportStock;
use App\Exports\ExportReports;
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
    
// public function reconcileStock()
// {
//     // Find missing invoices
//     $missingInvoices = DB::select("
//         SELECT id, invoice, 'legacies' AS missing_from_table
//         FROM legacies
//         WHERE invoice NOT IN (
//             SELECT invoice
//             FROM stocks
//         )
//         UNION
//         SELECT id, invoice, 'stocks' AS missing_from_table
//         FROM stocks
//         WHERE invoice NOT IN (
//             SELECT invoice
//             FROM legacies
//         )
//     ");

//     // Update mismatch and comment columns for missing invoices
//     foreach ($missingInvoices as $missingInvoice) {
//         $tableToUpdate = ($missingInvoice->missing_from_table === 'legacies') ? 'legacies' : 'stocks';

//         DB::table($tableToUpdate)
//             ->where('id', $missingInvoice->id)
//             ->update([
//                 'mismatch' => true,
//                 'comment' => 'Invoice not in ' . $missingInvoice->missing_from_table . ' stock records',
//             ]);
//     }

//     // Find quantity mismatches
//     $quantityMismatches = DB::select("
//         SELECT l.id, l.invoice, l.qty AS current_qty, s.qty AS physical_qty
//         FROM legacies AS l
//         INNER JOIN stocks AS s ON l.invoice = s.invoice
//         WHERE l.qty != s.qty
//     ");

//     // Update mismatch and comment columns for quantity mismatches
//     foreach ($quantityMismatches as $mismatch) {
//         DB::table('legacies')
//             ->where('id', $mismatch->id)
//             ->update([
//                 'mismatch' => true,
//                 'comment' => 'Mismatch: Current quantity is ' . $mismatch->current_qty . ', Physical quantity is ' . $mismatch->physical_qty,
//             ]);

//         DB::table('stocks')
//             ->where('id', $mismatch->id)
//             ->update([
//                 'mismatch' => true,
//                 'comment' => 'Mismatch: Current quantity is ' . $mismatch->physical_qty . ', Legacy quantity is ' . $mismatch->current_qty,
//             ]);
//     }

//     // Retrieve the data for the view
//     $missingInvoices = DB::select("SELECT id, invoice, 'legacies' AS missing_from_table FROM legacies WHERE invoice NOT IN (SELECT invoice FROM stocks) UNION SELECT id, invoice, 'stocks' AS missing_from_table FROM stocks WHERE invoice NOT IN (SELECT invoice FROM legacies)");

//     $quantityMismatches = DB::select("SELECT l.id, l.invoice, l.qty AS current_qty, s.qty AS physical_qty FROM legacies AS l INNER JOIN stocks AS s ON l.invoice = s.invoice WHERE l.qty != s.qty");
    
//     // Return the reconciliation view with the data
//     return view('reconciliation.index', compact('missingInvoices', 'quantityMismatches'));
// }

public function reconcileStock()
{
    // Find missing invoices
    $missingInvoices = DB::select("
        SELECT id, invoice, 'legacies' AS missing_from_table, comment
        FROM legacies
        WHERE MONTH(created_at) = MONTH(NOW()) AND YEAR(created_at) = YEAR(NOW())
        AND invoice NOT IN (
            SELECT invoice
            FROM stocks
            WHERE MONTH(created_at) = MONTH(NOW()) AND YEAR(created_at) = YEAR(NOW())
        )
        UNION
        SELECT id, invoice, 'stocks' AS missing_from_table, comment
        FROM stocks
        WHERE MONTH(created_at) = MONTH(NOW()) AND YEAR(created_at) = YEAR(NOW())
        AND invoice NOT IN (
            SELECT invoice
            FROM legacies
            WHERE MONTH(created_at) = MONTH(NOW()) AND YEAR(created_at) = YEAR(NOW())
        )
    ");

    // Update mismatch and comment columns for missing invoices
    foreach ($missingInvoices as $missingInvoice) {
        $tableToUpdate = ($missingInvoice->missing_from_table === 'legacies') ? 'legacies' : 'stocks';

        DB::table($tableToUpdate)
            ->where('id', $missingInvoice->id)
            ->update([
                'mismatch' => true,
                'comment' => 'Invoice not in ' . $missingInvoice->missing_from_table . ' stock records',
            ]);
    }

    // Find quantity mismatches
    $quantityMismatches = DB::select("
        SELECT l.id, l.invoice, l.qty AS current_qty, s.qty AS physical_qty, l.comment
        FROM legacies AS l
        INNER JOIN stocks AS s ON l.invoice = s.invoice
        WHERE MONTH(l.created_at) = MONTH(NOW()) AND YEAR(l.created_at) = YEAR(NOW())
        AND l.qty != s.qty
    ");

    // Update mismatch and comment columns for quantity mismatches
    foreach ($quantityMismatches as $mismatch) {
        DB::table('legacies')
            ->where('id', $mismatch->id)
            ->update([
                'mismatch' => true,
                'comment' => 'System Stock quantity is ' . $mismatch->current_qty . ', Physical Stock quantity is ' . $mismatch->physical_qty,
            ]);

        DB::table('stocks')
            ->where('id', $mismatch->id)
            ->update([
                'mismatch' => true,
                'comment' => 'System Stock quantity is ' . $mismatch->physical_qty . ', Physical Stock quantity is ' . $mismatch->current_qty,
            ]);
    }
    
    return view('reconciliation.index', compact('missingInvoices', 'quantityMismatches'));
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
