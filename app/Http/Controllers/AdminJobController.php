<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Location;
use App\Models\Skill;
use App\Notifications\JobStatusChanged;
use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\HtmlString;

class AdminJobController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $jobs = Job::all();
        $user = auth()->user(); 
        $jobs = Job::where('user_id', $user->id)->get();

        return view('admin.jobs.index', compact('jobs'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function jobFormView()
    {
        $locations = Location::all();
        $skills = Skill::all();

        return view('admin.jobs.create', compact('locations', 'skills'));
    }



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function jobApplication()
    {
        $user = auth()->user(); // Get the currently logged-in user
    $jobApplications = JobApplication::whereHas('job', function ($query) use ($user) {
        $query->where('user_id', $user->id);
    })->get();

        return view('admin.jobs.applications', compact('jobApplications'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'skill_id' => 'required|array',
            'skill_id.*' => 'exists:skills,id',
            'applicants' => 'required',
            'vacancies' => 'required',
            'location_id' => 'required',
            'type' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation Failed', 'errors' => $validator->errors()], 422);
        }

         $description_html = $request->input('description');
        $description_plain = strip_tags($description_html); // Remove HTML tags

        // Set the plain text content in the hidden input field
        $request->merge([
            'description' => new HtmlString($description_plain),
        ]);

        $jobData = $request->only([
            'title',
            'description',
            'applicants',
            'vacancies',
            'location_id',
            'type',
        ]);

        $jobData['user_id'] = Auth::user()->id; // Assign the authenticated user's ID to the job
        $jobData['status'] = true; // Set the default status to "open" (true)

        $job = Job::create($jobData);

        $skillIds = $request->input('skill_id');
        $job->skills()->sync($skillIds);

        $location = Location::find($job->location_id)->pluck('name', 'id');
        $skills = Skill::whereIn('id', $skillIds)->pluck('skill', 'id');

        $status = $job->status ? 'open' : 'closed';

        return redirect()->route('admin.jobs.show')->with('success', 'Job created successfully. Status: '.$status);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required',
            'description' => 'required',
            'skill_id' => 'required|array',
            'skill_id.*' => 'exists:skills,id',
            'applicants' => 'required',
            'vacancies' => 'required',
            'location_id' => 'required',
            'type' => 'required',
        ]);

         $description_html = $request->input('description');
        $description_plain = strip_tags($description_html); // Remove HTML tags

        // Set the plain text content in the hidden input field
        $request->merge([
            'description' => new HtmlString($description_plain),
        ]);
        $jobData = $request->only([
            'title',
            'description',
            'skill_id',
            'applicants',
            'vacancies',
            'location_id',
            'type',
        ]);

        $jobData['user_id'] = auth()->id(); // Assign the authenticated user's ID to the job
        $jobData['status'] = true; // Set the default status to "open" (true)

        $job = Job::create($jobData);

        $skillIds = $request->input('skill_id');
        $job->skills()->attach($skillIds);

        $status = $job->status ? 'open' : 'closed';

        return redirect()->route('admin.jobs.show')->with('success', 'Job created successfully. Status: '.$status);
    }


    public function toggleStatus(Request $request, $jobId)
    {
        $job = Job::findOrFail($jobId);

        $job->status = !$job->status; // Toggle the status
        $job->save();

        $status = $job->status ? 'open' : 'closed';

        // return response()->json(['message' => 'Job status toggled successfully. New status: '.$status]);
        return redirect()->back()->with('status', 'Job status toggled successfully. New status: '.$status);
    }

    // AUTHENTICATED TOGGLE
    // public function toggleStatus(Request $request, $job)
    // {
    //     $job = Job::findOrFail($job);

    //     // Only allow the user who created the job to toggle the status
    //     if (!auth()->check() || $job->user_id !== auth()->user()->id) {
    //         return response()->json(['message' => 'You are not authorized to toggle the status of this job.'], 403);
    //     }

    //     $job->status = !$job->status; // Toggle the status
    //     $job->save();

    //     $status = $job->status ? 'open' : 'closed';

    //     return redirect()->back()->with('status', 'Job status toggled successfully. New status: '.$status);
    // }


    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Job $jobs)
    {
        // $jobs = Job::all();
        $user = auth()->user(); 
        $jobs = Job::where('user_id', $user->id)->get();

        return view('admin.jobs.show', ['jobs' => $jobs]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Job $job)

    {
         $locations = Location::all();
        $skills = Skill::all();
        return view('admin.jobs.edit', compact('job','locations', 'skills'));
    }

    /**
     * Update the specified resource in stora+ge.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $job)
{
    $validator = Validator::make($request->all(), [
        'title' => 'required',
        'description' => 'required',
        'skill_id' => 'required|array',
        'skill_id.*' => 'exists:skills,id',
        'applicants' => 'required',
        'vacancies' => 'required',
        'location_id' => 'required',
        'type' => 'required',
    ]);

    if ($validator->fails()) {
        return response()->json(['message' => 'Validation Failed', 'errors' => $validator->errors()], 422);
    }

    $description_html = $request->input('description');
    $description_plain = strip_tags($description_html); // Remove HTML tags

    // Set the plain text content in the hidden input field
    $request->merge([
        'description' => new HtmlString($description_plain),
    ]);

    $job = Job::findOrFail($job);

    $jobData = $request->only([
        'title',
        'description',
        'applicants',
        'vacancies',
        'location_id',
        'type',
    ]);

    $job->update($jobData);

    $skillIds = $request->input('skill_id');
    $job->skills()->sync($skillIds);

    $location = Location::find($job->location_id)->pluck('name', 'id');
    $skills = Skill::whereIn('id', $skillIds)->pluck('skill', 'id');

    $status = $job->status ? 'open' : 'closed';

    return redirect()->back()->with('success', 'Job updated successfully. Status: '.$status);
}
    

    /**
     * Pause the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function pause(Job $job)
    {
        $job->status = 'paused';
        $job->save();

        return redirect()->route('admin.jobs.show')->with('success', 'Job paused successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
   public function destroy(Job $job)
    {
        // Detach related job_skills records
        $job->skills()->detach();

        // Delete the job
        $job->delete();

        return redirect()->route('admin.jobs.show')->with('success', 'Job deleted successfully');
    }
   
  public function getAppliedJobsBySeeker()
    {
        // Retrieve the logged-in seeker's ID from the authenticated user
        $seekerId = auth()->user()->id;
    
        $applications = JobApplication::where('seeker_id', $seekerId)->get();
    
        $appliedJobs = [];
    
        foreach ($applications as $application) {
            $job = Job::find($application->job_id);
            $jobStatus = $job->status;
            $applicationStatus = $application->status;
            $appliedJobs[] = [
                'job_id' => $job->id,
                'title' => $job->title,
                'job_status' => $jobStatus,
                'application_status' => $applicationStatus,
            ];
        }
    
        return response()->json(['applied_jobs' => $appliedJobs]);
    }



    public function getAppliedJobs()
    {
        $jobs = Job::whereHas('jobApplications')->get();

        return response()->json(['applied_jobs' => $jobs]);
    }
}
