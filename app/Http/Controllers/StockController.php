<?php

namespace App\Http\Controllers;

use App\Owner;
use App\Stock;
use App\Warehouse;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin,staff');
    }

    public function index()
    {
        $warehouse = Warehouse::orderBy('id', 'ASC')
            ->get()
            ->pluck('name', 'id');

        // $bay = Bay::orderBy('name', 'ASC')
        //     ->get()
        //     ->pluck('name', 'ASC');

        $owner = Owner::orderBy('id', 'ASC')
            ->get()
            ->pluck('name', 'ASC');

        // $garden = Garden::orderBy('id', 'ASC')
        //     ->get()
        //     ->pluck('name', 'ASC');
        // $grade = Grade::orderBy('grade', 'ASC')
        //     ->get()
        //     ->pluck('grade', 'ASC');

        // $packageType = PackageType::orderBy('name', 'ASC')
        //     ->get()
        //     ->pluck('name', 'ASC');

        $stock = Stock::all();

        return view('stock.index', compact('warehouse', 'bay', 'owner', 'garden', 'grade', 'packageType', 'stock'));
    }

    public function create()
    {
        $warehouses = Warehouse::all();
        $bays = Bay::all();
        $owners = Owner::all();
        $gardens = Garden::all();
        $grades = Grade::all();
        $packageType = PackageType::all();

        return view('stock.create', compact('warehouses', 'bays', 'owners', 'gardens', 'grades', 'packageType'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'warehouse_id' => 'required',
            'bay_id' => 'required',
            'owner_id' => 'required',
            'garden_id' => 'required',
            'grade_id' => 'required',
            'packageType_id' => 'required',
            'qty' => 'required',
            'year' => 'required',
            'invoice' => 'required',
        ]);

        Stock::create($request->all());

        return redirect()->route('stock.index')->with('success', 'Stock created succesfully');
    }

    public function update(Request $request, Stock $stock)
    {
        $request->validate([
            'warehouse_id' => 'required',
            'bay_id' => 'required',
            'owner_id' => 'required',
            'garden_id' => 'required',
            'grade_id' => 'required',
            'packageType_id' => 'required',
            'qty_id' => 'required',
            'year' => 'required',
            'invoice' => 'required',
        ]);

        $stock->update($request->all());

        return redirect()->route('stock.index')->with('success', 'Stock updated succesfully');
    }

    public function destroy(Stock $stock)
    {
        $stock->delete();

        return redirect()->route('stock.index')->with('success', 'Stock deleted succesfully');
    }
}
