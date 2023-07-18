<?php

namespace App\Http\Controllers;

use App\Models\HighestLevel;
use App\Models\Interest;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\Location;
use App\Models\Seeker;
use App\Models\SeekerProfile;
use App\Models\Skill;
// use App\Notifications\JobStatusChanged;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SeekerController extends Controller
{
    use Notifiable;

    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['register', 'registerSave', 'login', 'loginAction']);
    }

    public function register()
    {
        return response()->json(['message' => 'nuku successful']);
    }



  public function seekerProfilePartner($id, $applicationId)
{
    // Retrieve the seeker data based on the ID
    $seeker = Seeker::find($id);
    $profile = SeekerProfile::where('seeker_id', $id)->first();
    $level = HighestLevel::where('id', $profile->highest_level_id)->first();

    // Retrieve the skill names that belong to the seeker
    $skills = $profile->skills()->pluck('skill')->toArray();

    // Retrieve the interests that belong to the seeker
    $interests = $seeker->interests()->pluck('interest')->toArray();

    // Retrieve the job application based on the application ID
    $jobApplication = JobApplication::find($applicationId);

    // Determine the status of the job application
    $status = $jobApplication->status;

    return view('jobs.profileaplicant', compact('seeker', 'profile', 'level', 'skills', 'interests', 'status', 'applicationId'));
}
 public function seekerProfileAdmin($id, $applicationId)
{
    // Retrieve the seeker data based on the ID
    $seeker = Seeker::find($id);
    $profile = SeekerProfile::where('seeker_id', $id)->first();
    $level = HighestLevel::where('id', $profile->highest_level_id)->first();

    // Retrieve the skill names that belong to the seeker
    $skills = $profile->skills()->pluck('skill')->toArray();

    // Retrieve the interests that belong to the seeker
    $interests = $seeker->interests()->pluck('interest')->toArray();

    // Retrieve the job application based on the application ID
    $jobApplication = JobApplication::find($applicationId);

    // Determine the status of the job application
    $status = $jobApplication->status;

    return view('admin.jobs.seekerapply', compact('seeker', 'profile', 'level', 'skills', 'interests', 'status', 'applicationId'));
}

    public function registerSave(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => 'required|numeric|unique:seekers',
            'password' => 'required',
            'email' => 'required|email|unique:seekers',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $seeker = Seeker::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'email' => $request->email,
        ]);

        $token = $seeker->createToken('auth-token')->plainTextToken;

        return response()->json(['message' => 'Registration successful', 'seeker' => $seeker, 'token' => $token]);
    }

    public function login()
    {
        return response()->json(['message' => 'Login successful']);
    }

    public function loginAction(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|phone',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
        $credentials = $request->only('phone', 'password');
        $remember = $request->boolean('remember');
        if (!Auth::guard('seeker')->attempt($credentials, $remember)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }
        $seeker = Auth::guard('seeker')->user();
        $token = $seeker->createToken('auth-token')->plainTextToken;

        return response()->json(['message' => 'Login successful', 'seeker' => $seeker, 'token' => $token]);

        // return response()->json(['message' => 'Login successful', 'seeker' => $seeker]);
    }

    public function logout(Request $request)
    {
        Auth::guard('seeker')->logout();

        $request->session()->invalidate();

        return response()->json(['message' => 'I am logout']);
    }

public function setupProfile(Request $request)
{
    $validator = Validator::make($request->all(), [
        'date_of_birth' => 'required|date',
        'id_number' => 'required',
        'location_id' => 'required',
        'highest_level_id' => 'required',
        'school' => 'required',
        'year_of_completion' => 'required|numeric',
        'skill_id' => 'required|array',
        'skill_id.*' => 'exists:skills,id',
        'interest_id' => 'required|array',
        'interest_id.*' => 'exists:interests,id',
        'resume' => 'required',
    ]);

    if ($validator->fails()) {
        return response()->json(['message' => 'Validation error', 'errors' => $validator->errors()], 422);
    }

    $data = $request->only([
        'date_of_birth',
        'id_number',
        'highest_level_id',
        'school',
        'year_of_completion',
    ]);

    $resumeContent = base64_decode($request->input('resume'));
    $resumeFileName = uniqid().'.pdf'; // Assuming the resume is in PDF format

    Storage::disk('public')->put($resumeFileName, $resumeContent);

    $data['resume'] = $resumeFileName;

    $seekerId = $request->user()->id;

    $seekerProfile = SeekerProfile::updateOrCreate(['seeker_id' => $seekerId], $data);

    $skillIds = $request->input('skill_id');
    $seekerProfile->skills()->sync($skillIds);

    $interestIds = $request->input('interest_id');
    $seekerProfile->interests()->sync($interestIds);

    $locationId = $request->input('location_id');
    $seekerProfile->locations()->sync([$locationId]);

    $location = Location::find($locationId);
    $skills = Skill::whereIn('id', $skillIds)->get();
    $interests = Interest::whereIn('id', $interestIds)->get();
    $highestLevel = HighestLevel::find($data['highest_level_id']);

    $responseData = [
        'message' => 'Profile setup successful',
        'data' => [
            'seeker_profile' => $seekerProfile,
            'location' => $location,
            'skills' => $skills,
            'interests' => $interests,
            'highest_level' => $highestLevel,
        ],
    ];

    return response()->json($responseData);
}

    public function getAllJobs()
    {
        $jobs = Job::with(['user', 'user.company'])->get();

        $formattedJobs = $jobs->map(function ($job) {
            return [
                'id' => $job->id,
                'title' => $job->title,
                'description' => $job->description,
                'skills' => $job->skills,
                'applicants' => $job->applicants,
                'vacancies' => $job->vacancies,
                'location' => $job->location,
                'type' => $job->type,
                'status' => $job->status ? 'open' : 'closed', // Include the job status
                'company' => [
                    'name' => $job->user->company->name ?? null,
                    'profile_pic' => $job->user->company->profile_pic ?? null,
                ],
                'created_at' => $job->created_at,
                'updated_at' => $job->updated_at,
            ];
        });

        return response()->json(['jobs' => $formattedJobs]);
    }

    public function dashboard()
    {
    }

    // public function applyForJob(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'job_id' => 'required|exists:jobs,id',
    //         'cv_file' => 'required|max:2048',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['error' => $validator->errors()], 422);
    //     }

    //     $seekerId = $request->user()->id;

    //     $jobId = $request->input('job_id');
    //     $cvFile = $request->file('cv_file');

    //     // if (!$cvFile->isValid()) {
    //     //     return response()->json(['error' => 'Invalid CV file'], 400);
    //     // }

    //     $job = Job::where('id', $jobId)->first();

    //     if (!$job) {
    //         return response()->json(['error' => 'Invalid job'], 404);
    //     }

    //     // $cvPath = $cvFile->store('cv_files', 'public');
    //     $jobApplication = new JobApplication();
    //     $jobApplication->seeker_id = $seekerId;
    //     $jobApplication->job_id = $jobId;
    //     $jobApplication->cv_file = '';
    //     $jobApplication->status = 'in review'; // Set the default status
    //     $jobApplication->save();

    //     // Notify the job creator about the new job application and status
    //     // $job->user->notify(new JobStatusChanged($jobApplication));

    //     return response()->json(['message' => 'Job application submitted successfully']);
    // }

public function applyForJob(Request $request)
{
    $validator = Validator::make($request->all(), [
        'job_id' => 'required|exists:jobs,id',
        'cv_file' => 'required',
    ]);

    if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()], 422);
    }

    $user = $request->user();
    $seekerId = $user->id;
    $jobId = $request->input('job_id');
    $cvFileContent = base64_decode($request->input('cv_file'));

    $job = Job::find($jobId);

    if (!$job) {
        return response()->json(['error' => 'Invalid job'], 404);
    }

    $cvFileName = uniqid().'.pdf';

    Storage::disk('public')->put($cvFileName, $cvFileContent);

    $jobApplication = new JobApplication();
    $jobApplication->seeker_id = $seekerId;
    $jobApplication->job_id = $jobId;
    $jobApplication->cv_file = $cvFileName; // Assign the file name, not the content
    $jobApplication->status = 'in review';
    $jobApplication->save();

    // $job->user->notify(new JobStatusChanged($jobApplication));

    return response()->json(['message' => 'Job application submitted successfully']);
}

    public function getJobApplications()
    {
        $seekerId = Auth::user()->id;

        $jobApplications = JobApplication::where('seeker_id', $seekerId)->get();

        return response()->json(['job_applications' => $jobApplications]);
    }

    public function getJobApplication($jobApplicationId)
    {
        $seekerId = Auth::user()->id;

        $jobApplication = JobApplication::where('id', $jobApplicationId)
            ->where('seeker_id', $seekerId)
            ->with('job')
            ->first();

        if (!$jobApplication) {
            return response()->json(['error' => 'Job application not found'], 404);
        }

        return response()->json(['job_application' => $jobApplication]);
    }

    // public function updateJobStatus(Request $request, $jobId)
    // {
    //     $job = Job::findOrFail($jobId);

    //     // Only allow the user who created the job to update the status
    //     if ($job->user_id !== Auth::user()->id) {
    //         return response()->json(['message' => 'You are not authorized to update the status of this job.'], 403);
    //     }

    //     $validatedData = $request->validate([
    //         'status' => 'required|in:approve,reject',
    //     ]);

    //     $job->status = $validatedData['status'];
    //     $job->save();

    //     // Notify the seeker about the job status update
    //     foreach ($job->jobApplications as $jobApplication) {
    //         $jobApplication->seeker->notify(new JobStatusChanged($jobApplication));
    //     }

    //     return response()->json(['message' => 'Job status updated successfully']);
    // }

    public function updateJobApplicationStatus(Request $request, $jobApplicationId)
    {
        $jobApplication = JobApplication::findOrFail($jobApplicationId);

        $validator = Validator::make($request->all(), [
            'status' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $status = $request->input('status');
        $jobApplication->status = $status ? 'accepted' : 'rejected';
        $jobApplication->save();

        $message = 'Job application status updated successfully';

        return redirect()->back()->with('message', $message);
    }


    public function updateJobApplicationStatuspartner(Request $request, $jobApplicationId)
    {
        $jobApplication = JobApplication::findOrFail($jobApplicationId);

        $validator = Validator::make($request->all(), [
            'status' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $status = $request->input('status');
        $jobApplication->status = $status ? 'accepted' : 'rejected';
        $jobApplication->save();

        $message = 'Job application status updated successfully';

        return redirect()->back()->with('message', $message);
    }
    
    public function getMatchingJobsBySkills(Request $request)
    {
        $seekerId = auth()->user()->id;
        $seekerProfile = SeekerProfile::where('seeker_id', $seekerId)->first();

        if (!$seekerProfile) {
            return response()->json(['error' => 'Seeker profile not found'], 404);
        }

        $seekerSkills = $seekerProfile->skills()->pluck('id');

        $matchingJobs = Job::whereHas('skills', function ($query) use ($seekerSkills) {
            $query->whereIn('skills.id', $seekerSkills);
        })->get();

        return response()->json(['matching_jobs' => $matchingJobs]);
    }
    public function updateSeekerProfile(Request $request): JsonResponse
    {
        $seeker = Auth::user();
        
        $validator = Validator::make($request->all(), [
            'date_of_birth' => 'required|date',
            'id_number' => 'required',
            'location_id' => 'required',
            'highest_level_id' => 'required',
            'school' => 'required',
            'year_of_completion' => 'required|numeric',
            'skill_id' => 'required|array',
            'skill_id.*' => 'exists:skills,id',
            'interest_id' => 'required|array',
            'interest_id.*' => 'exists:interests,id',
            'resume' => 'required',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['message' => 'Validation error', 'errors' => $validator->errors()], 422);
        }
    
        $data = $request->only([
            'date_of_birth',
            'id_number',
            'highest_level_id',
            'school',
            'year_of_completion',
        ]);
    
        $resumeContent = base64_decode($request->input('resume'));
        $resumeFileName = uniqid().'.pdf';
    
        Storage::disk('public')->put($resumeFileName, $resumeContent);
    
        $data['resume'] = $resumeFileName;
    
        $seeker = $request->user();
        $seekerProfile = $seeker->profile;
    
        $seekerProfile->update($data);
    
        $skillIds = $request->input('skill_id');
        $seekerProfile->skills()->sync($skillIds);
    
        $interestIds = $request->input('interest_id');
        $seeker->interests()->sync($interestIds);
    
        $locationId = $request->input('location_id');
        $seekerProfile->locations()->sync([$locationId]);
    
        $location = Location::find($locationId);
        $skills = Skill::whereIn('id', $skillIds)->get();
        $interests = Interest::whereIn('id', $interestIds)->get();
        $highestLevel = HighestLevel::find($data['highest_level_id']);
    
        $responseData = [
            'message' => 'Profile updated successfully',
            'data' => [
                'seeker' => $seeker,
                'profile' => $seekerProfile,
                'location' => $location,
                'skills' => $skills,
                'interests' => $interests,
                'highest_level' => $highestLevel,
            ],
        ];
    
        return response()->json($responseData);
    }
    
    public function getSeekerProfile(Request $request): JsonResponse
    {
         $seeker = Auth::user();
    $profile = SeekerProfile::where('seeker_id', $seeker->id)->first();
        
        $seeker = $request->user();
        $profile = $seeker->profile;
        $level = $profile->highestLevel;
    
        $skills = $profile->skills()->pluck('skill')->toArray();
    
        $interests = $seeker->interests()->pluck('interest')->toArray();
    
        $responseData = [
            'seeker' => $seeker,
            'profile' => $profile,
            'level' => $level,
            'skills' => $skills,
            'interests' => $interests,
        ];
    
        return response()->json($responseData);
    }


}
