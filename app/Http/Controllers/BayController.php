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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $warehouse = Warehouse::orderBy('name', 'ASC')
            ->get()
            ->pluck('name', 'id');

        $bays = Bay::all();

        return view('bays.index', compact('warehouse'));
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
        $warehouse = Warehouse::orderBy('name', 'ASC')
            ->get()
            ->pluck('name', 'id');

        $this->validate($request, [
            'name' => 'required|string',
            'warehouse_id' => 'required',
        ]);

        $input = $request->all();

        Bay::create($input);

        return response()->json([
            'success' => true,
            'message' => 'Bays Created',
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
        $warehouse = Warehouse::orderBy('name', 'ASC')
            ->get()
            ->pluck('name', 'id');
        $bay = Bay::find($id);

        return $bay;
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
        $warehouse = Warehouse::orderBy('name', 'ASC')
            ->get()
            ->pluck('name', 'id');

        $this->validate($request, [
            'name' => 'required|string',
            'warehouse_id' => 'required',
        ]);

        $bay = Bay::findOrFail($id);

        $bay->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Bay Update',
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
            ->rawColumns(['warehouse_name', 'show_photo', 'action'])->make(true);
    }
}
