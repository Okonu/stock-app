<?php

namespace App\Http\Controllers;

use App\Garden;
use App\Bay;
use App\Warehouse;
use App\Grade;
use App\Owner;
use App\User;
use App\Package;
use App\Stock;
use PDF;
use App\Exports\ExportStock;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class StockController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('role:admin,staff');
    }

    public function index()
    {
        $warehouse = Warehouse::orderBy('name', 'ASC')
            ->get()
            ->pluck('name', 'id');

        $bay = Bay::orderBy('name', 'ASC')
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
        $stock = Stock::all();

        return view('stocks.index', compact('warehouse', 'bay', 'owner', 'garden', 'grade', 'package'));
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

        $bay = Bay::orderBy('name', 'ASC')
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
            'bay_id' => 'required',
            'owner_id' => 'required',
            'garden_id' => 'required',
            'grade_id' => 'required',
            'package_id' => 'required',
            'invoice' => 'required|string',
            'qty' => 'required|string',
            'year' => 'required|string',
            'remark' => 'required|string'
        ]);

        $input = $request->all();
        Stock::create($input);

        // $stock = Stock::findOrFail($request->warehouse_id);
        // $stock->invoice += $request->invoice;
        // $stock->save();

        return response()->json([
            'success' => true,
            'message' => 'Stock created successfully'
        ]);
    }

    public function  show($id)
    {
        //
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
            'bay_id' => 'required',
            'owner_id' => 'required',
            'garden_id' => 'required',
            'grade_id' => 'required',
            'package_id' => 'required',
            'invoice' => 'required|string',
            'qty' => 'required|string',
            'year' => 'required|string',
            'remark' => 'required|string'
        ]);

        $stock = Stock::findOrFail($id);
        $stock->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Stock Updated Successfully'
        ]);
    }

    public function apiStocks()
    {
        $stock = Stock::all();

        return Datatables::of($stock)
            ->addColumn('warehouse_name', function($stock){
                return $stock->warehouse->name;
            })
            ->addColumn('bay_name', function($stock){
            return $stock->bay->name;
            })
            ->addColumn('owner_name', function($stock){
                return $stock->owner->name;
            })
            ->addColumn('garden_name', function($stock){
                return $stock->garden->name;
            })
            ->addColumn('grade_name', function($stock){
                return $stock->grade->name;
            })
            ->addColumn('package_name', function($stock){
                return $stock->package->name;
            })
            ->addColumn('action', function($stock){
                return '<a onclick="editForm('.$stock->id.')" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-edit"></i>Edit</a>' .
                    '<a onclick="deleteData('.$stock->id.')" class="btn btn-danger btn-xs"><i class="glyphicon glyphicon-trash"></i>Delete</a>';

            })
            ->rawColumns(['warehouse_name', 'bay_name', 'owner_name', 'garden_name', 'grade_name', 'package_name', 'action'])->make(true);
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

    public function exportExcel()
    {
        return (new ExportStock)->download('stock.xlsx');
    }
}
