<?php

namespace App\Http\Controllers;

use App\Models\Interest;
use Illuminate\Http\Request;

class InterestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $interest = Interest::all();

        return response()->json($interest);
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
            'interest' => 'required|max:255',
        ]);

        $interest = Interest::create($request->all());

        return response()->json($interest);
    }

    /**
     * Display the specified resource.
     */
    public function show(Interest $interest)
    {
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Interest $interest)
    {
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Interest $interest)
    {
        $request->validate([
            'interest' => 'required|max:255',
        ]);

        $interest->update($request->all());

        return response()->json($interest);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Interest $interest)
    {
        $interest->delete();

        return response()->json(['message' => 'Deleted Successfully']);
    }
}
