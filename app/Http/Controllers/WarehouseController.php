<?php

namespace App\Http\Controllers;

use App\Http\Requests\Warehouse\StoreWarehouseRequest;
use App\Http\Requests\Warehouse\UpdateWarehouseRequest;
use App\Models\Warehouse;
use App\Models\WarehouseBay;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\Datatables;

class WarehouseController extends Controller
{
    public function __construct()
    {
        // $this->middleware('role:admin,staff');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): View
    {
        $warehouses = Warehouse::all();

        return view('warehouses.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(): View
    {
        return view('warehouses.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(StoreWarehouseRequest $request): RedirectResponse
    {
        $warehouse = Warehouse::create([
            'name' => $request->input('name'),
        ]);

        foreach ($request->input('bays') as $bayName) {
            $warehouse->bays()->create(['name' => $bayName]);
        }

        return redirect()->route('warehouses.index')->with('success', 'Warehouse created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Warehouse $warehouse)
    {
        $warehouse->load('bays');

        $bays = $warehouse->bays->pluck('name')->toArray();

        return response()->json([
            'id' => $warehouse->id,
            'name' => $warehouse->name,
            'bays' => $bays,
        ]);
    }

    public function update(UpdateWarehouseRequest $request, Warehouse $warehouse): RedirectResponse
    {
        $warehouse->update([
            'name' => $request->input('name'),
        ]);

        $warehouse->bays()->delete();

        foreach ($request->input('bays') as $bayName) {
            $warehouse->bays()->create(['name' => $bayName]);
        }

        return redirect()->route('warehouses.index')->with('success', 'Warehouse updated successfully.');
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
            'message' => 'Warehouse deleted',
        ]);
    }

    public function apiWarehouses()
    {
        $warehouses = Warehouse::with('bays')->get();

        return Datatables::of($warehouses)
            ->addColumn('bays', function ($warehouse) {
                $bays = $warehouse->bays->pluck('name')->implode(', ');

                return $bays;
            })
            ->addColumn('action', function ($warehouse) {
                return '<a onclick="editForm('.$warehouse->id.')" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-edit"></i> Edit</a> '.
                    '<a onclick="deleteData('.$warehouse->id.')" class="btn btn-danger btn-xs"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function apiBays($warehouseId)
    {
        $bays = WarehouseBay::where('warehouse_id', $warehouseId)->get();

        return Datatables::of($bays)
            ->addColumn('action', function ($bay) {
                return '<a onclick="editForm('.$bay->id.')" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-edit"></i> Edit</a> '.
                    '<a onclick="deleteData('.$bay->id.')" class="btn btn-danger btn-xs"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
