<?php

namespace App\Http\Controllers;

use App\Owner;
use Illuminate\Http\Request;
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
    public function index()
    {
        $owners = Owner::all();

        return view('owners.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'address' => 'required',
            'email' => 'required|unique:owners',
            'telephone' => 'required',
        ]);

        Owner::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Owner Created',
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
        // //
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

        return $owner;
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
            'address' => 'required|string|min:2',
            'email' => 'required|string|email|max:255|unique:owner',
            'telephone' => 'required|string|min:2',
        ]);

        $owner = Owner::findOrFail($id);

        $owner->update($request->all());

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
