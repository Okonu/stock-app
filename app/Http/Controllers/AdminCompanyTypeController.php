<?php

namespace App\Http\Controllers;

use App\Models\CompanyType;
use App\Models\Company;
use Illuminate\Http\Request;

class AdminCompanyTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $companyTypes = CompanyType::all();

        foreach ($companyTypes as $companyType) {
            $companyType->in_use = Company::where('company_type_id', $companyType->id)->exists();
        }

        return view('admin.settings.companytype', compact('companyTypes'));
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

        return redirect()->back();
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

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(Request $request, $id)
{
    $companyType = CompanyType::findOrFail($id);
    $companyType->delete();

    // Redirect back to the page
    return redirect()->back();
}
}
