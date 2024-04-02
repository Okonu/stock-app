<?php

namespace App\Http\Controllers;

use App\Http\Requests\Package\StorePackageRequest;
use App\Http\Requests\Package\UpdatePackageRequest;
use App\Models\Package;
use Illuminate\View\View;
use Yajra\DataTables\Datatables;

class PackageController extends Controller
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
        $packages = Package::all();

        return view('packages.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(StorePackageRequest $request)
    {
        Package::create($request->validated());

        return response()->json([
           'success' => true,
           'message' => 'Package Type Created',
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
        $package = Package::find($id);

        if ($package) {
            return response()->json($package, 200);
        } else {
            return response()->json(['error' => 'Package not found'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePackageRequest $request, $id)
    {
        $package = Package::findOrFail($id);

        $package->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Package Type Updated',
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
        Package::destroy($id);

        return response()->json([
            'success' => true,
            'message' => 'Package Type Deleted',
        ]);
    }

    public function apiPackages()
    {
        $packages = Package::all();

        return Datatables::of($packages)
            ->addColumn('action', function ($packages) {
                return '<a onclick="editForm('.$packages->id.')" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-edit"></i> Edit</a> '.
                    '<a onclick="deleteData('.$packages->id.')" class="btn btn-danger btn-xs"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
            })
            ->rawColumns(['action'])->make(true);
    }
}
