<?php

namespace App\Http\Controllers;

use App\Http\Requests\Garden\StoreGardenRequest;
use App\Models\Garden;
use App\Models\Owner;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;

class GardenController extends Controller
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
        $owner = Owner::orderBy('name', 'ASC')
            ->get()
            ->pluck('name', 'id');

        $gardens = Garden::all();

        return view('gardens.index', compact('owner'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(StoreGardenRequest $request)
    {
        // $owner = Owner::orderBy('name', 'ASC')
        //     ->get()
        //     ->pluck('name', 'id');

        // $this->validate($request, [
        //     'name' => 'required|string',
        //     'owner_id' => 'required',
        // ]);

        // $input = $request->all();

        $garden = Garden::create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Garden Created',
        ]);
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
        $owner = Owner::orderBy('name', 'ASC')
            ->get()
            ->pluck('name', 'id');
        $garden = Garden::find($id);

        return $garden;
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
        $owner = Owner::orderBy('name', 'ASC')
            ->get()
            ->pluck('name', 'id');

        $this->validate($request, [
            'name' => 'required|string',
            'owner_id' => 'required',
        ]);

        $garden = Garden::findOrFail($id);

        $garden->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Gardens Updated',
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
        $garden = Garden::findOrFail($id);

        Garden::destroy($id);

        return response()->json([
            'success' => true,
            'message' => 'Garden Deleted',
        ]);
    }

    public function apiGardens()
    {
        $garden = Garden::all();

        return Datatables::of($garden)
            ->addColumn('owner_name', function ($garden) {
                return $garden->owner->name;
            })
            ->addColumn('action', function ($garden) {
                return '<a onclick="editForm('.$garden->id.')" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-edit"></i> Edit</a> '.
                    '<a onclick="deleteData('.$garden->id.')" class="btn btn-danger btn-xs"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
            })
            ->rawColumns(['owner_name', 'action'])->make(true);
    }
}
