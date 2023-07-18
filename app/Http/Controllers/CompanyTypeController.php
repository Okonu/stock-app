<?php

namespace App\Http\Controllers;

use App\Models\CompanyType;
use Illuminate\Http\Request;

class CompanyTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $companyType = CompanyType::all();

        return response()->json($companyType);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
        ]);

        $companyType = CompanyType::create($request->all());

        return response()->json($companyType);
    }

    /**
     * Display the specified resource.
     */
    public function show(CompanyType $companyType)
    {
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CompanyType $companyType)
    {
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CompanyType $companyType)
    {
        $request->validate([
            'name' => 'required|max:255',
        ]);

        $companyType->update($request->all());

        return response()->json($companyType);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CompanyType $companyType)
    {
        $companyType->delete();

        return response()->json(['message' => 'Successfully deleted']);
    }
}
