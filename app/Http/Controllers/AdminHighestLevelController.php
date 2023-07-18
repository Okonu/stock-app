<?php

namespace App\Http\Controllers;
use App\Models\SeekerProfile;

use App\Models\HighestLevel;
use Illuminate\Http\Request;

class AdminHighestLevelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
           $highestLevels = HighestLevel::all();

    foreach ($highestLevels as $level) {
        $level->in_use = SeekerProfile::where('highest_level_id', $level->id)->exists();
    }

    return view('admin.settings.level', compact('highestLevels'));

       
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

        $highestLevel = HighestLevel::create($request->all());

       return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(HighestLevel $highestLevel)
    {
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(HighestLevel $highestLevel)
    {
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, HighestLevel $highestLevel)
    {
        $request->validate([
            'name' => 'required|max:255',
        ]);

        $highestLevel->update($request->all());

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    

     public function delete(Request $request, $id)
{
    $level = HighestLevel::findOrFail($id);
    $level->delete();

    // Redirect back to the page
    return redirect()->back();
}
}
