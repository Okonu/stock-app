<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminLocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $locations = Location::all();

    foreach ($locations as $location) {
        $location->in_use = DB::table('companies')
            ->where('location_id', $location->id)
            ->orWhereExists(function ($query) use ($location) {
                $query->select(DB::raw(1))
                    ->from('jobs')
                    ->whereRaw('jobs.location_id = companies.location_id')
                    ->where('jobs.location_id', $location->id);
            })
            ->exists();
    }

    return view('admin.settings.location', compact('locations'));
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

        $location = Location::create($request->all());

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Location $location)
    {
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Location $location)
    {
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Location $location)
    {
        $request->validate([
            'name' => 'required|max:255',
        ]);

        $location->update($request->all());

       return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    

     public function delete(Request $request, $id)
{
    $location = Location::findOrFail($id);
    $location->delete();

    // Redirect back to the page
    return redirect()->back();
}
}
