<?php

namespace App\Http\Controllers;

use App\Exports\ExportStock;
use App\Garden;
use App\Grade;
use App\Imports\StockImport;
use App\Owner;
use App\Package;
use App\Stock;
use App\User;
use App\Warehouse;
use App\WarehouseBay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
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

    public function index()
    {
        return view('stocks.index');
    }
    // public function index()
    // {
    //     $stocks = Stock::get();

    //     $data = [];

    //     foreach ($stocks as $stock) {
    //         $data[] = [
    //             // 'id' => $stock->id,
    //             // 'user_id' => $stock->user_id,
    //             // 'warehouse_id' => $stock->warehouse_id,
    //             // 'warehouse_bay_id' => $stock->warehouse_bay_id,
    //             // 'owner_id' => $stock->owner_id,
    //             // 'garden_id' => $stock->garden_id,
    //             // 'grade_id' => $stock->grade_id,
    //             // 'package_id' => $stock->package_id,
    //             'invoice' => $stock->invoice,
    //             'qty' => $stock->qty,
    //             'year' => $stock->year,
    //             'remark' => $stock->remark,
    //             'mismatch' => $stock->mismatch,
    //             'created_at' => $stock->created_at,
    //             'updated_at' => $stock->updated_at,
    //             'grade_name' => $stock->grade ? $stock->grade->name : 'N/A',
    //             'user_name' => $stock->user ? $stock->user->name : 'N/A',
    //             'warehouse_name' => $stock->warehouse ? $stock->warehouse->name : 'N/A',
    //             'warehouse_bay_name' => $stock->warehouse_bay ? $stock->warehouse_bay->name : 'N/A',
    //             'owner_name' => $stock->owner ? $stock->owner->name : 'N/A',
    //             'garden_name' => $stock->garden ? $stock->garden->name : 'N/A',
    //             'package_name' => $stock->package ? $stock->package->name : 'N/A',
    //         ];
    //     }

    //     return response()->json([
    //         // 'draw' => 1,
    //         // 'recordsTotal' => count($stocks),
    //         // 'recordsFiltered' => count($stocks),
    //         'data' => $data,
    //     ]);
    // }

    public function create()
    {
        // Create
    }

    public function show($id)
    {
    }

    public function edit($id)
    {
        $stock = Stock::find($id);

        return $stock;
    }

    public function apiStocks()
    {
        $stocks = Stock::with(['user', 'warehouse', 'bay', 'owner', 'garden', 'grade', 'package'])
            ->get();

        return Datatables::of($stocks)
            ->addColumn('user_name', function ($stock) {
                return $stock->user ? $stock->user->name : null;
            })
            ->addColumn('warehouse_name', function ($stock) {
                return $stock->warehouse ? $stock->warehouse->name : null;
            })
            ->addColumn('warehouse_bay_name', function ($stock) {
                return $stock->bay ? $stock->bay->name : null;
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
            ->make(true);
    }

    public function ImportExcel(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|mimes:xls,xlsx',
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            Excel::import(new StockImport(), $file);

            return redirect()->back()->with(['success' => 'Uploaded file successfully !']);
        }

        return redirect()->back()->with(['error' => 'Please choose file to upload.']);
    }

    // public function exportStockAll()
    // {
    //     $stock = Stock::all();
    //     $pdf = \PDF::loadView('stocks.stockAllPDF', compact('stock'));

    //     return $pdf->download('stock.pdf');
    // }

    // public function exportStock($id)
    // {
    //     $stock = Stock::findOrFail($id);
    //     $pdf = \PDF::loadView('stocks.exportStockPDF', compact('stock'));

    //     return $pdf->download($stock->id.'_stock.pdf');
    // }

    public function exportExcel()
    {
        return (new ExportStock())->download('stock.xlsx');
    }
}
