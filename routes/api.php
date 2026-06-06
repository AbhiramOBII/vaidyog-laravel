<?php

use App\Http\Controllers\Api\Auth\JobSeekerAuthController;
use App\Http\Controllers\Api\Auth\RecruiterAuthController;
use App\Http\Controllers\Api\JobSeeker\ApplicationController as JobSeekerApplicationController;
use App\Http\Controllers\Api\JobSeeker\DashboardController as JobSeekerDashboardController;
use App\Http\Controllers\Api\JobSeeker\PlanController as JobSeekerPlanController;
use App\Http\Controllers\Api\JobSeeker\ProfileController as JobSeekerProfileController;
use App\Http\Controllers\Api\JobSeeker\ProfileSectionController;
use App\Http\Controllers\Api\JobSeeker\SavedJobController;
use App\Http\Controllers\Api\PublicJobController;
use App\Http\Controllers\Api\Recruiter\ApplicationController as RecruiterApplicationController;
use App\Http\Controllers\Api\Recruiter\CandidateController;
use App\Http\Controllers\Api\Recruiter\DashboardController as RecruiterDashboardController;
use App\Http\Controllers\Api\Recruiter\JobController as RecruiterJobController;
use App\Http\Controllers\Api\Recruiter\PlanController as RecruiterPlanController;
use App\Http\Controllers\Api\Recruiter\ProfileController as RecruiterProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public API Routes
|--------------------------------------------------------------------------
*/

Route::prefix('jobs')->name('api.jobs.')->group(function () {
    Route::get('/', [PublicJobController::class, 'index'])->name('index');
    Route::get('/{job}', [PublicJobController::class, 'show'])->name('show');
});

/*
|--------------------------------------------------------------------------
| Job Seeker Auth Routes
|--------------------------------------------------------------------------
*/

Route::prefix('jobseeker')->name('api.jobseeker.')->group(function () {

    Route::post('/register', [JobSeekerAuthController::class, 'register'])->name('register');
    Route::post('/login', [JobSeekerAuthController::class, 'login'])->name('login');

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [JobSeekerAuthController::class, 'logout'])->name('logout');
        Route::get('/me', [JobSeekerAuthController::class, 'me'])->name('me');

        // Dashboard
        Route::get('/dashboard', [JobSeekerDashboardController::class, 'index'])->name('dashboard');

        // Profile
        Route::get('/profile', [JobSeekerProfileController::class, 'show'])->name('profile.show');
        Route::put('/profile', [JobSeekerProfileController::class, 'update'])->name('profile.update');
        Route::post('/profile/photo', [JobSeekerProfileController::class, 'updatePhoto'])->name('profile.photo');
        Route::post('/profile/resume', [JobSeekerProfileController::class, 'updateResume'])->name('profile.resume');

        // Profile Sections (Education, Employment, Certifications, Languages, etc.)
        Route::prefix('/profile')->name('profile.')->group(function () {
            Route::get('/education', [ProfileSectionController::class, 'index'])->defaults('section', 'education')->name('education.index');
            Route::post('/education', [ProfileSectionController::class, 'store'])->defaults('section', 'education')->name('education.store');
            Route::put('/education/{id}', [ProfileSectionController::class, 'update'])->defaults('section', 'education')->name('education.update');
            Route::delete('/education/{id}', [ProfileSectionController::class, 'destroy'])->defaults('section', 'education')->name('education.destroy');

            Route::get('/employment', [ProfileSectionController::class, 'index'])->defaults('section', 'employment')->name('employment.index');
            Route::post('/employment', [ProfileSectionController::class, 'store'])->defaults('section', 'employment')->name('employment.store');
            Route::put('/employment/{id}', [ProfileSectionController::class, 'update'])->defaults('section', 'employment')->name('employment.update');
            Route::delete('/employment/{id}', [ProfileSectionController::class, 'destroy'])->defaults('section', 'employment')->name('employment.destroy');

            Route::get('/certifications', [ProfileSectionController::class, 'index'])->defaults('section', 'certification')->name('certifications.index');
            Route::post('/certifications', [ProfileSectionController::class, 'store'])->defaults('section', 'certification')->name('certifications.store');
            Route::put('/certifications/{id}', [ProfileSectionController::class, 'update'])->defaults('section', 'certification')->name('certifications.update');
            Route::delete('/certifications/{id}', [ProfileSectionController::class, 'destroy'])->defaults('section', 'certification')->name('certifications.destroy');

            Route::get('/languages', [ProfileSectionController::class, 'index'])->defaults('section', 'language')->name('languages.index');
            Route::post('/languages', [ProfileSectionController::class, 'store'])->defaults('section', 'language')->name('languages.store');
            Route::put('/languages/{id}', [ProfileSectionController::class, 'update'])->defaults('section', 'language')->name('languages.update');
            Route::delete('/languages/{id}', [ProfileSectionController::class, 'destroy'])->defaults('section', 'language')->name('languages.destroy');

            Route::get('/projects', [ProfileSectionController::class, 'index'])->defaults('section', 'project')->name('projects.index');
            Route::post('/projects', [ProfileSectionController::class, 'store'])->defaults('section', 'project')->name('projects.store');
            Route::put('/projects/{id}', [ProfileSectionController::class, 'update'])->defaults('section', 'project')->name('projects.update');
            Route::delete('/projects/{id}', [ProfileSectionController::class, 'destroy'])->defaults('section', 'project')->name('projects.destroy');

            Route::get('/publications', [ProfileSectionController::class, 'index'])->defaults('section', 'publication')->name('publications.index');
            Route::post('/publications', [ProfileSectionController::class, 'store'])->defaults('section', 'publication')->name('publications.store');
            Route::put('/publications/{id}', [ProfileSectionController::class, 'update'])->defaults('section', 'publication')->name('publications.update');
            Route::delete('/publications/{id}', [ProfileSectionController::class, 'destroy'])->defaults('section', 'publication')->name('publications.destroy');

            Route::get('/presentations', [ProfileSectionController::class, 'index'])->defaults('section', 'presentation')->name('presentations.index');
            Route::post('/presentations', [ProfileSectionController::class, 'store'])->defaults('section', 'presentation')->name('presentations.store');
            Route::put('/presentations/{id}', [ProfileSectionController::class, 'update'])->defaults('section', 'presentation')->name('presentations.update');
            Route::delete('/presentations/{id}', [ProfileSectionController::class, 'destroy'])->defaults('section', 'presentation')->name('presentations.destroy');

            Route::get('/research', [ProfileSectionController::class, 'index'])->defaults('section', 'research')->name('research.index');
            Route::post('/research', [ProfileSectionController::class, 'store'])->defaults('section', 'research')->name('research.store');
            Route::put('/research/{id}', [ProfileSectionController::class, 'update'])->defaults('section', 'research')->name('research.update');
            Route::delete('/research/{id}', [ProfileSectionController::class, 'destroy'])->defaults('section', 'research')->name('research.destroy');

            Route::get('/honours-awards', [ProfileSectionController::class, 'index'])->defaults('section', 'honours_award')->name('honours-awards.index');
            Route::post('/honours-awards', [ProfileSectionController::class, 'store'])->defaults('section', 'honours_award')->name('honours-awards.store');
            Route::put('/honours-awards/{id}', [ProfileSectionController::class, 'update'])->defaults('section', 'honours_award')->name('honours-awards.update');
            Route::delete('/honours-awards/{id}', [ProfileSectionController::class, 'destroy'])->defaults('section', 'honours_award')->name('honours-awards.destroy');

            Route::get('/affiliations', [ProfileSectionController::class, 'index'])->defaults('section', 'affiliation')->name('affiliations.index');
            Route::post('/affiliations', [ProfileSectionController::class, 'store'])->defaults('section', 'affiliation')->name('affiliations.store');
            Route::put('/affiliations/{id}', [ProfileSectionController::class, 'update'])->defaults('section', 'affiliation')->name('affiliations.update');
            Route::delete('/affiliations/{id}', [ProfileSectionController::class, 'destroy'])->defaults('section', 'affiliation')->name('affiliations.destroy');
        });

        // Applications
        Route::get('/applications', [JobSeekerApplicationController::class, 'index'])->name('applications.index');
        Route::get('/applications/{application}', [JobSeekerApplicationController::class, 'show'])->name('applications.show');
        Route::post('/jobs/{job}/apply', [JobSeekerApplicationController::class, 'apply'])->name('jobs.apply');
        Route::delete('/applications/{application}', [JobSeekerApplicationController::class, 'withdraw'])->name('applications.withdraw');

        // Saved Jobs
        Route::get('/saved-jobs', [SavedJobController::class, 'index'])->name('saved-jobs.index');
        Route::post('/saved-jobs/{job}', [SavedJobController::class, 'save'])->name('saved-jobs.save');
        Route::delete('/saved-jobs/{job}', [SavedJobController::class, 'unsave'])->name('saved-jobs.unsave');

        // Plan
        Route::get('/my-plan', [JobSeekerPlanController::class, 'show'])->name('plan');
        Route::get('/plans', [JobSeekerPlanController::class, 'index'])->name('plans.index');
    });
});

/*
|--------------------------------------------------------------------------
| Recruiter Auth Routes
|--------------------------------------------------------------------------
*/

Route::prefix('recruiter')->name('api.recruiter.')->group(function () {

    Route::post('/register', [RecruiterAuthController::class, 'register'])->name('register');
    Route::post('/login', [RecruiterAuthController::class, 'login'])->name('login');

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [RecruiterAuthController::class, 'logout'])->name('logout');
        Route::get('/me', [RecruiterAuthController::class, 'me'])->name('me');

        // Dashboard
        Route::get('/dashboard', [RecruiterDashboardController::class, 'index'])->name('dashboard');

        // Profile
        Route::get('/profile', [RecruiterProfileController::class, 'show'])->name('profile.show');
        Route::put('/profile', [RecruiterProfileController::class, 'update'])->name('profile.update');
        Route::post('/profile/logo', [RecruiterProfileController::class, 'updateLogo'])->name('profile.logo');

        // Jobs
        Route::get('/jobs', [RecruiterJobController::class, 'index'])->name('jobs.index');
        Route::post('/jobs', [RecruiterJobController::class, 'store'])->name('jobs.store');
        Route::get('/jobs/{job}', [RecruiterJobController::class, 'show'])->name('jobs.show');
        Route::put('/jobs/{job}', [RecruiterJobController::class, 'update'])->name('jobs.update');
        Route::delete('/jobs/{job}', [RecruiterJobController::class, 'destroy'])->name('jobs.destroy');

        // Applications
        Route::get('/applications', [RecruiterApplicationController::class, 'index'])->name('applications.index');
        Route::get('/applications/{application}', [RecruiterApplicationController::class, 'show'])->name('applications.show');
        Route::put('/applications/{application}/status', [RecruiterApplicationController::class, 'updateStatus'])->name('applications.status');
        Route::put('/applications/{application}/ranking', [RecruiterApplicationController::class, 'updateRanking'])->name('applications.ranking');
        Route::get('/jobs/{job}/applications', [RecruiterApplicationController::class, 'forJob'])->name('jobs.applications');

        // Plan & Subscriptions
        Route::get('/my-plan', [RecruiterPlanController::class, 'show'])->name('plan');
        Route::get('/plans', [RecruiterPlanController::class, 'index'])->name('plans.index');

        // Candidates
        Route::get('/candidates/{userId}', [CandidateController::class, 'show'])->name('candidates.show');
    });
});
