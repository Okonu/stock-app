<?php

namespace App\Http\Controllers;

use App\Warehouse;
use Illuminate\Http\Request;
use Yajra\DataTables\Datatables;

class WarehouseController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin,staff');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $warehouses = Warehouse::all();

        return view('warehouses.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
           'name' => 'required|string|min:2',
        ]);

        Warehouse::create($request->all());

        return response()->json([
           'success' => true,
           'message' => 'Warehouses Created',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $warehouse = Warehouse::find($id);

        return $warehouse;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|string|min:2',
        ]);

        $warehouse = Warehouse::findOrFail($id);

        $warehouse->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Warehouses Update',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Warehouse::destroy($id);

        return response()->json([
            'success' => true,
            'message' => 'Warehouses Delete',
        ]);
    }

    public function apiWarehouses()
    {
        $warehouses = Warehouse::all();

        return Datatables::of($warehouses)
            ->addColumn('action', function ($warehouses) {
                return '<a onclick="editForm('.$warehouses->id.')" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-edit"></i> Edit</a> '.
                    '<a onclick="deleteData('.$warehouses->id.')" class="btn btn-danger btn-xs"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
            })
            ->rawColumns(['action'])->make(true);
    }
}
