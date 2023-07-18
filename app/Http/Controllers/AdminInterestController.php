<?php

namespace App\Http\Controllers;

use App\Models\Interest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminInterestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         $interests = Interest::all();

    foreach ($interests as $interest) {
        $interest->in_use = DB::table('seeker_interests')
            ->where('interest_id', $interest->id)
            ->exists();
    }

    return view('admin.settings.interest', compact('interests'));
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

        return redirect()->back();
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

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
   

     public function delete(Request $request, $id)
{
    $interest = Interest::findOrFail($id);
    $interest->delete();

    // Redirect back to the page
    return redirect()->back();
}
}
