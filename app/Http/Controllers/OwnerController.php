<?php

namespace App\Http\Controllers;

use App\Http\Requests\Owner\StoreOwnerRequest;
use App\Http\Requests\Owner\UpdateOwnerRequest;
use App\Models\Owner;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;

class OwnerController extends Controller
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
        $owners = Owner::all();

        return view('owners.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(StoreOwnerRequest $request): JsonResponse
    {

        Owner::create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Owner Created',
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
        $owner = Owner::find($id);

        if($owner) {
            return response()->json($owner, 200);
        } else {
            return response()->json(['error' => 'Owner Not Found'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateOwnerRequest $request, $id)
    {
        $owner = Owner::findOrFail($id);

        $owner->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Owner Updated',
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
        Owner::destroy($id);

        return response()->json([
            'success' => true,
            'message' => 'Owner Deleted',
        ]);
    }

    public function apiOwners()
    {
        $owner = Owner::all();

        return Datatables::of($owner)
            ->addColumn('action', function ($owner) {
                return '<a onclick="editForm('.$owner->id.')" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-edit"></i> Edit</a> '.
                    '<a onclick="deleteData('.$owner->id.')" class="btn btn-danger btn-xs"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
            })
            ->rawColumns(['action'])->make(true);
    }
}
