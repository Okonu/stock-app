<?php
namespace App\Http\Controllers;
use App\Models\Job;
use App\Models\User;
use App\Models\JobApplication;
use App\Models\HighestLevel;
use App\Models\Seeker;
use App\Models\Skill;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminReportController extends Controller
{
     public function index()
    {
        
    $jobCount = Job::count();
    $userCount = User::where('level', '!=', 'admin')->count();
    $seekerCount = Seeker::count();
    $partnerCount = User::where('level', 'partner')->count();
    $applicationCount = JobApplication::count();
    $approvedApplicationCount = JobApplication::where('status', 'accepted')->count();
    $rejectedApplicationCount = JobApplication::where('status', 'rejected')->count();

    $popularSkills = DB::table('skills')
    ->leftJoin('seeker_skills', 'skills.id', '=', 'seeker_skills.skill_id')
    ->select('skills.skill', DB::raw('COUNT(seeker_skills.seeker_id) as seekers_count'))
    ->groupBy('skills.skill')
    ->orderByDesc('seekers_count')
    ->get();

$popularJobLocations = DB::table('jobs')
    ->join('locations', 'jobs.location_id', '=', 'locations.id')
    ->select('locations.name', DB::raw('COUNT(*) as count'))
    ->groupBy('locations.name')
    ->orderByDesc('count')
    ->get();

 
$popularSeekerLocations = DB::table('seeker_locations')
    ->join('locations', 'seeker_locations.location_id', '=', 'locations.id')
    ->select('locations.name', DB::raw('COUNT(*) as count'))
    ->groupBy('locations.name')
    ->orderByDesc('count')
    ->get();

   


    $data = [
        'jobCount' => $jobCount,
        'userCount' => $userCount,
        'seekerCount' => $seekerCount,
        'partnerCount' => $partnerCount,
        'applicationCount' => $applicationCount,
        'approvedApplicationCount' => $approvedApplicationCount,
        'rejectedApplicationCount' => $rejectedApplicationCount,
        'popularSkills' => $popularSkills,
        
        'popularJobLocations' => $popularJobLocations,
        'popularSeekerLocations' => $popularSeekerLocations,
        
    ];

    return view('admin.report.report', $data);

        
        // return view('admin.report.report');
        
    }
}
