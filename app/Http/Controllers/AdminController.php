<?php

namespace App\Http\Controllers;
use App\Models\Job;
use App\Models\JobApplication;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function index()
    // {

        
        
    //     return view('dashboard');
    // }


    public function index()
    {
        $userId = auth()->id();
    
        $jobCount = Job::where('user_id', $userId)->count();
    
        $jobApplicationCount = JobApplication::whereHas('job', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->count();
    
        // $activeJobCount = Job::where('user_id', $userId)->count();
        $activeJobCount = Job::where('user_id', $userId)->where('status', 1)->count();
    
        $acceptedApplicationCount = JobApplication::whereHas('job', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->where('status', 'accepted')->count();
    
        $jobApplications = JobApplication::whereHas('job', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->get();
    
        return view('admin.dashboard', compact('jobCount', 'jobApplicationCount', 'activeJobCount', 'acceptedApplicationCount', 'jobApplications'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
