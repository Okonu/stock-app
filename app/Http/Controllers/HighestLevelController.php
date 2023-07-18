<?php

namespace App\Http\Controllers;

use App\Models\HighestLevel;
use Illuminate\Http\Request;

class HighestLevelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $highestLevel = HighestLevel::all();

        return response()->json($highestLevel);
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

        return response()->json($highestLevel, 201);
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

        return response()->json($highestLevel);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HighestLevel $highestLevel)
    {
        $highestLevel->delete();

        return response()->json(['message' => 'Successfully deleted']);
    }
}
