<?php

namespace App\Http\Controllers;

use App\Http\Requests\Garden\StoreGardenRequest;
use App\Http\Requests\Garden\UpdateGardenRequest;
use App\Models\Garden;
use App\Models\Owner;
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
        $owners = Owner::orderBy('name', 'ASC')->pluck('name', 'id');
        $garden = Garden::find($id);

        if($garden) {
            return response()->json($garden, 200);
        } else {
            return response()->json(['error' => 'Garden Not Found'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateGardenRequest $request, $id)
    {
        $garden = Garden::findOrFail($id);

        $garden->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Garden Updated',
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
