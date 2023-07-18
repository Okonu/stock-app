<?php

use App\Http\Controllers\CompanyTypeController;
use App\Http\Controllers\HighestLevelController;
use App\Http\Controllers\InterestController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\SeekerController;
use App\Http\Controllers\SkillController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PasswordResetController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/seeker/register', [SeekerController::class, 'registerSave']);
Route::post('/seeker/login', [SeekerController::class, 'loginAction']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/seeker/profile', [SeekerController::class, 'setupProfile']);
    Route::get('/jobs', [SeekerController::class, 'getAllJobs']);
    Route::post('/skills', [SkillController::class, 'store']);
    Route::get('/skills', [SkillController::class, 'index']);
    Route::put('/skills/{skill}', [SkillController::class, 'destroy']);
    Route::get('/skills/{skill}/edit', [SkillController::class, 'update']);
    Route::post('/location', [LocationController::class, 'store']);
    Route::get('/location', [LocationController::class, 'index']);
    Route::post('/highestLevel', [HighestLevelController::class, 'store']);
    Route::get('/highestLevel', [HighestLevelController::class, 'index']);
    Route::post('/companyType', [CompanyTypeController::class, 'store']);
    Route::get('/companyType', [CompanyTypeController::class, 'index']);
    Route::post('/interest', [InterestController::class, 'store']);
    Route::get('/interest', [InterestController::class, 'index']);
    Route::post('/jobApplications', [SeekerController::class, 'applyForJob']);
    Route::get('/jobApplications', [SeekerController::class, 'getJobApplications']);
    Route::get('/jobs/applied/', [JobController::class, 'getAppliedJobsBySeeker']);
    
    //  Route::get('/seeker/profile', [SeekerController::class, 'getSeekerProfile']);
    // Route::post('/seeker/profile', [SeekerController::class, 'updateSeekerProfile']);
});
// Route::put('/jobs/{jobId}/status', 'JobController@updateJobStatus');
// Route::put('/jobs/{jobId}/status', [JobController::class, 'updateJobStatus']);
// Route::get('/seekers/{seekerId}/applied-jobs', 'JobController@getAppliedJobsBySeeker');
// Route::get('/seekers/{seekerId}/applied-jobs', 'JobController@getAppliedJobsBySeeker');
// Route::get('/jobs/applied/{seekerId}', [JobController::class, 'getAppliedJobsBySeeker']);
Route::put('/jobs/{jobId}/status', [JobController::class, 'updateJobStatus']);

Route::put('/jobs/{jobId}/toggle', [JobController::class, 'toggleStatus']);

// Route::put('/applications/{applicationId}/status', [JobController::class, 'changeApplicationStatus']);

// Route::put('/job-applications/{applicationId}/status', [JobController::class, 'updateApplicationStatus']);
Route::put('job-applications/{jobApplicationId}/status', [SeekerController::class, 'updateJobApplicationStatus']);

Route::get('/jobs/relevant', [JobController::class, 'relevantJobs']);

Route::get('/jobs/matching', [SeekerController::class, 'getMatchingJobsBySkills']);

Route::post('/password-reset/request', [PasswordResetController::class, 'requestReset']);
Route::post('/password-reset/reset', [PasswordResetController::class, 'resetPassword']);
