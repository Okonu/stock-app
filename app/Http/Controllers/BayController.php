<?php

namespace App\Http\Controllers;

use App\Bay;
use App\Warehouse;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class BayController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin,staff');
    }

    public function index()
    {
        $warehouse = Warehouse::orderBy('name', 'ASC')
            ->get()
            ->pluck('name', 'id');

        $bays = Bay::all();

        return view('bays.index', compact('warehouse'));
    }

    public function create(Warehouse $warehouse)
    {
        $warehouses = Warehouse::all();
        return view('bays.create', compact('warehouse'));
    }

    public function store(Request $request, Warehouse $warehouse)
    {   
        $request->validate([
            'name' => 'required',
            'bays' => 'required',
        ]);

        $bayNames = $request->input('bays');

        foreach ($bayNames as $bayName) {
            $bay = new Bay([
                'name' => $bayName,
                'warehouse_id' => $warehouse->id,
            ]);

            $bay->save();
        }

        return redirect()->route('warehouses.show', $warehouse)->with('success', 'Bays created successfully.');
    }

    public function show($id)
    {
        // Implement your logic for showing a specific bay
    }

    public function edit(Warehouse $warehouse, Bay $bay)
    {
        return view('bays.edit', compact('warehouse', 'bay'));
    }

    public function update(Request $request, Warehouse $warehouse, Bay $bay)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $bay->name = $request->input('name');
        $bay->save();

        return redirect()->route('warehouses.show', $warehouse)->with('success', 'Bay updated successfully.');
    }

    public function destroy(Warehouse $warehouse, Bay $bay)
    {
        $bay->delete();

        return redirect()->route('warehouses.show', $warehouse)->with('success', 'Bay deleted successfully.');
    }

    public function apiBays()
    {
        $bays = Bay::all();

        return DataTables::of($bays)
            ->addColumn('warehouse_name', function ($bay) {
                return $bay->warehouse->name;
            })
            ->addColumn('action', function ($bay) {
                return '<a onclick="editForm('.$bay->id.')" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-edit"></i> Edit</a> '.
                    '<a onclick="deleteData('.$bay->id.')" class="btn btn-danger btn-xs"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
            })
            ->rawColumns(['warehouse_name', 'show_photo', 'action'])
            ->make(true);
    }
}
