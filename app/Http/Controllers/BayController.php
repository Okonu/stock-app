<?php

namespace App\Http\Controllers;

use App\Bay;
use App\Warehouse;
use Illuminate\Http\Request;

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

    public function create()
    {
    }

    public function store(Request $request)
    {
        $warehouse = Warehouse::orderBy('name', 'ASC')
        ->get()
        ->pluck('name');

        $this->validate($request, [
            'name' => 'required|string',
            'warehouse_id' => 'required',
        ]);

        Bay::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Bay Created',
        ]);
    }

    public function edit($id)
    {
        $warehouse = Warehouse::orderBy('name', 'ASC')
            ->get()
            ->pluck('name', 'id');

        $bay = Bay::find($id);

        return $bay;
    }

    public function update(Request $request, $id)
    {
        $warehouse = Warehouse::orderBy('name', 'ASC')
            ->get()
            ->pluck('name', 'id');

        $this->validate($request, [
            'warehouse_id' => 'required',
            'name' => 'required',
        ]);

        $bay = Bay::findOrFail($id);
        $bay->update($request->all());

        return response()->json([
            'success' => true,
            'messag' => 'Bay Updated',
        ]);
    }

    public function destroy($id)
    {
        $bay = Bay::findOrFail($id);

        Bay::destroy($id);

        return response()->json([
            'success' => true,
            'message' => 'Bay Deleted',
        ]);
    }

    public function apiBays()
    {
        $bay = Bay::all();

        return Datatables::of($bay)
            ->addColumn('warehouse_name', function ($bay) {
                return $bay->warehouse->name;
            })

            ->addColumn('action', function ($bay) {
                return '<a onclick="editForm('.$bay->id.')" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-edit"></i> Edit</a> '.
                    '<a onclick="deleteData('.$bay->id.')" class="btn btn-danger btn-xs"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
            })
            ->rawColumns(['warehouse_name', 'action'])->make(true);
    }
}
