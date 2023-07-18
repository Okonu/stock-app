<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\AdminJobController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\CompanyTypeController;
use App\Http\Controllers\InterestController;
use App\Http\Controllers\HighestLevelController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\AdminSkillController;
use App\Http\Controllers\AdminCompanyTypeController;
use App\Http\Controllers\AdminInterestController;
use App\Http\Controllers\AdminHighestLevelController;
use App\Http\Controllers\AdminLocationController;
use App\Http\Controllers\PartnerApplicationController;
use App\Http\Controllers\AdminReportController;
use App\Http\Controllers\AdminCommunicateController;
use App\Http\Controllers\SeekerController;
use App\Http\Controllers\ResumeController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', function () {
    return view('welcome');
});

    Route::prefix('auth')->group(function () {
    Route::get('register', [AuthController::class, 'register'])->name('register');
    Route::post('register', [AuthController::class, 'registerSave'])->name('register.save');

    Route::get('login', [AuthController::class, 'login'])->name('login');
    Route::post('login', [AuthController::class, 'loginAction'])->name('login.action');
});

// Routes accessible only after successful registration
Route::middleware(['auth', 'checkRegistrationStatus'])->group(function () {
    Route::middleware(['checkApprovalStatus'])->group(function () {
        Route::get('/profile', [AuthController::class, 'accountinfo'])->name('profile');
        Route::post('/profile', [AuthController::class, 'updatepartner'])->name('profile');
       Route::post('/update-picture', [AuthController::class, 'updateProfilePicture'])->name('profile.updatepic');

        Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
        Route::get('/jobs/create', [JobController::class, 'jobFormView'])->name('jobs.create.view');
        Route::get('/jobs/show', [JobController::class, 'show'])->name('jobs.show');
        Route::post('/jobs', [JobController::class, 'create'])->name('jobs.create');
        Route::post('/jobs/{job}/pause', [JobController::class, 'pause'])->name('jobs.pause');
        Route::get('/jobs/{job}/edit', [JobController::class, 'edit'])->name('jobs.edit');
        Route::put('/jobs/toggle/{job}', [JobController::class, 'toggleStatus'])->name('jobs.toggles');
        Route::put('/jobs/{job}', [JobController::class, 'update'])->name('jobs.update');
        Route::delete('/jobs/{job}', [JobController::class, 'destroy'])->name('jobs.destroy');
        Route::get('/jobs/applications', [JobController::class, 'jobApplication'])->name('jobs.applications');

        Route::get('/seekers', [SeekerController::class, 'index'])->name('seekers.index');
        
        

      Route::get('/jobs/profileaplicant/{id}/{applicationId}', [SeekerController::class, 'seekerProfilePartner'])->name('jobs.profileaplicant');

     
         
         
        
 
    });
});

 
// Routes accessible only to admins
Route::middleware(['auth', 'checkAdminRole'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::post('/admin/approve/{userId}', [AuthController::class, 'approveUser'])->name('admin.approveUser');
    Route::post('/admin/deactivate/{userId}', [AuthController::class, 'deactivateUser'])->name('admin.deactivateUser');
    Route::post('/admin/rejectuser/{userId}', [AuthController::class, 'rejectUser'])->name('admin.rejectUser');

 Route::get('/admin/profile', [AuthController::class, 'adminaccountinfo'])->name('admin.profile');
    Route::post('/admin/profile', [AuthController::class, 'updateadmin'])->name('admin.profile.update');
    Route::post('/admin/update-picture', [AuthController::class, 'updateAdminProfilePicturea'])->name('adminprofile.updatepic');

    Route::get('/admin/jobs/create', [AdminJobController::class, 'jobFormView'])->name('admin.jobs.create.view');
    Route::get('/admin/jobs/show', [AdminJobController::class, 'show'])->name('admin.jobs.show');
    Route::post('/admin/jobs', [AdminJobController::class, 'create'])->name('admin.jobs.create');
    Route::post('/admin/jobs/{job}/pause', [AdminJobController::class, 'pause'])->name('admin.jobs.pause');
    Route::get('/admin/jobs/{job}/edit', [AdminJobController::class, 'edit'])->name('admin.jobs.edit');
    Route::put('/admin/jobs/{job}', [AdminJobController::class, 'update'])->name('admin.jobs.update');
    Route::delete('/admin/jobs/{job}', [AdminJobController::class, 'destroy'])->name('admin.jobs.destroy');
    

    Route::get('/jobs/seekerapply/{id}/{applicationId}', [SeekerController::class, 'seekerProfileAdmin'])->name('admin.jobs.seekerapply');
    Route::put('job-applications/{jobApplicationId}/status', [SeekerController::class, 'updateJobApplicationStatus'])->name('adminJobApplication.status');
     


    Route::get('/admin/jobs/applications', [AdminJobController::class, 'jobApplication'])->name('admin.jobs.applications');

    Route::post('/skills', [AdminSkillController::class, 'store'])->name('addSkills');
    Route::get('/settings/skills', [AdminSkillController::class, 'index'])->name('admin.settings.skills');
    Route::delete('/skills/{id}', [AdminSkillController::class, 'delete'])->name('deleteSkills');

    Route::post('/location', [AdminLocationController::class, 'store'])->name('addLocations');
    Route::delete('/location/{id}', [AdminLocationController::class, 'delete'])->name('deleteLocation');
    Route::get('/settings/location', [AdminLocationController::class, 'index'])->name('admin.settings.location');

    Route::post('/highestLevel', [AdminHighestLevelController::class, 'store'])->name('addHighestLevel');
    Route::get('/settings/level', [AdminHighestLevelController::class, 'index'])->name('admin.settings.level');
    Route::delete('/highestLevel/{id}', [AdminHighestLevelController::class, 'delete'])->name('deleteLevel');

    Route::post('/companyType', [AdminCompanyTypeController::class, 'store'])->name('addCompanyType');
    Route::get('/settings/companytype', [AdminCompanyTypeController::class, 'index'])->name('admin.settings.companytype');
    Route::delete('/companyType/{id}', [AdminCompanyTypeController::class, 'delete'])->name('deleteType');

    Route::post('/interest', [AdminInterestController::class, 'store'])->name('addInterest');
    Route::get('/settings/interest', [AdminInterestController::class, 'index'])->name('admin.settings.interest');
    Route::delete('/interest/{id}', [AdminInterestController::class, 'delete'])->name('deleteInterest');

    Route::post('/approve/{userId}', [AuthController::class, 'approveUser']);

    Route::post('/deactivate/{userId}', [AuthController::class, 'deactivateUser'])->name('admin.deactivateUser');
    Route::put('/jobs/{job}/toggle', [AdminJobController::class, 'toggleStatus'])->name('admin.jobs.toggle');

    Route::get('/admin/pending-applications', [AuthController::class, 'pendingApplications']);
    Route::get('/manage/application', [PartnerApplicationController::class, 'index'])->name('manage.application');
    Route::get('/communicate/message', [AdminCommunicateController::class, 'index'])->name('communicate.message');
    Route::post('/communicate/message', [AdminCommunicateController::class, 'sendSms'])->name('communicate.sms');
    Route::get('/report/report', [AdminReportController::class, 'index'])->name('report.report');
    Route::get('/manage/profile/{id}', [PartnerApplicationController::class, 'profile'])->name('manage.profile');


});

    // Routes accessible to both non-admin and admin users
    Route::post('/seeker/profile', [SeekerController::class, 'setupProfile']);
    Route::get('/jobs', [SeekerController::class, 'getAllJobs'])->name('jobs');
    Route::post('/jobApplications', [SeekerController::class, 'applyForJob'])->name('applyForJob');
    Route::get('/jobApplications/{jobId}', [JobController::class, 'getJobApplications'])->name('jobApplications');
    Route::put('job-applications/{jobApplicationId}/status', [SeekerController::class, 'updateJobApplicationStatuspartner'])->name('JobApplicationupdate.status');
    // Route for pending approval page
    Route::middleware(['auth'])->group(function () { Route::get('/auth/pending-approval', 
    function () { return view('auth.pending'); })->name('auth.pending');});

    Route::get('logout', [AuthController::class, 'logout'])->name('logout');
    
     
     
     Route::get('/resumes/{filename}', [ResumeController::class, 'show'])->name('resume.show');
