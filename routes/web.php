<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminLoginController;
use App\Http\Controllers\Auth\GoogleCallbackController;
use App\Http\Controllers\Auth\JobSeekerGoogleController;
use App\Http\Controllers\Auth\RecruiterGoogleController;
use App\Livewire\Admin\Applications\ApplicationBin as AdminApplicationBin;
use App\Livewire\Admin\Applications\ApplicationIndex as AdminApplicationIndex;
use App\Livewire\Admin\Applications\ApplicationShow as AdminApplicationShow;
use App\Livewire\Admin\Plans\AssignPlan;
use App\Livewire\Admin\Plans\FeaturedPlanIndex;
use App\Livewire\Admin\Plans\JobSeekerPlanForm;
use App\Livewire\Admin\Plans\JobSeekerPlanIndex;
use App\Livewire\Admin\Plans\RecruiterPlanForm;
use App\Livewire\Admin\Plans\RecruiterPlanIndex;
use App\Livewire\Admin\Jobs\JobBin as AdminJobBin;
use App\Livewire\Admin\Jobs\JobCreate as AdminJobCreate;
use App\Livewire\Admin\Jobs\JobEdit as AdminJobEdit;
use App\Livewire\Admin\Jobs\JobIndex as AdminJobIndex;
use App\Livewire\Admin\Jobs\JobPending as AdminJobPending;
use App\Livewire\Admin\Jobs\JobShow as AdminJobShow;
use App\Livewire\Admin\JobSeekers\JobSeekerBulkImport;
use App\Livewire\Admin\JobSeekers\JobSeekerCreate;
use App\Livewire\Admin\JobSeekers\JobSeekerEdit;
use App\Livewire\Admin\JobSeekers\JobSeekerIndex;
use App\Livewire\Admin\JobSeekers\JobSeekerShow;
use App\Livewire\Admin\Recruiters\RecruiterCreate;
use App\Livewire\Admin\Recruiters\RecruiterEdit;
use App\Livewire\Admin\Recruiters\RecruiterIndex;
use App\Livewire\Admin\Recruiters\RecruiterShow;
use App\Livewire\Frontend\Auth\RecruiterGoogleOnboarding;
use App\Livewire\Frontend\Auth\RecruiterRegistration;
use App\Livewire\Frontend\Jobs\JobDetail;
use App\Livewire\Frontend\Jobs\JobIndex as PublicJobIndex;
use App\Livewire\Frontend\Profile\PublicProfile;
use App\Livewire\JobSeeker\Applications\ApplicationIndex as JobSeekerApplicationIndex;
use App\Livewire\JobSeeker\Applications\ApplicationShow as JobSeekerApplicationShow;
use App\Livewire\JobSeeker\SavedJobs\SavedJobIndex;
use App\Livewire\Recruiter\Applications\ApplicationForJob as RecruiterApplicationForJob;
use App\Livewire\Recruiter\Applications\ApplicationIndex as RecruiterApplicationIndex;
use App\Livewire\Recruiter\Applications\ApplicationShow as RecruiterApplicationShow;
use App\Livewire\Recruiter\Jobs\JobCreate as RecruiterJobCreate;
use App\Livewire\Recruiter\Jobs\JobEdit as RecruiterJobEdit;
use App\Livewire\Recruiter\Jobs\JobIndex as RecruiterJobIndex;
use App\Livewire\Recruiter\Jobs\JobShow as RecruiterJobShow;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.home');
})->name('home');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {

    Route::middleware('guest.admin')->group(function () {
        Route::get('/login', [AdminLoginController::class, 'showLogin'])->name('login');
        Route::post('/login', [AdminLoginController::class, 'login'])->name('login.submit');
    });

    Route::middleware('auth.admin')->group(function () {
        Route::post('/logout', [AdminLoginController::class, 'logout'])->name('logout');
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Job Seekers
        Route::get('/job-seekers', JobSeekerIndex::class)->name('job-seekers.index')->middleware('admin.permission:job_seekers.view');
        Route::get('/job-seekers/create', JobSeekerCreate::class)->name('job-seekers.create')->middleware('admin.permission:job_seekers.create');
        Route::get('/job-seekers/bulk-import', JobSeekerBulkImport::class)->name('job-seekers.bulk-import')->middleware('admin.permission:job_seekers.create');
        Route::get('/job-seekers/{user}', JobSeekerShow::class)->name('job-seekers.show')->middleware('admin.permission:job_seekers.view');
        Route::get('/job-seekers/{user}/edit', JobSeekerEdit::class)->name('job-seekers.edit')->middleware('admin.permission:job_seekers.edit');

        // Recruiters
        Route::get('/recruiters', RecruiterIndex::class)->name('recruiters.index')->middleware('admin.permission:recruiters.view');
        Route::get('/recruiters/create', RecruiterCreate::class)->name('recruiters.create')->middleware('admin.permission:recruiters.create');
        Route::get('/recruiters/{user}', RecruiterShow::class)->name('recruiters.show')->middleware('admin.permission:recruiters.view');
        Route::get('/recruiters/{user}/edit', RecruiterEdit::class)->name('recruiters.edit')->middleware('admin.permission:recruiters.edit');

        // Job Postings
        Route::get('/jobs', AdminJobIndex::class)->name('jobs.index')->middleware('admin.permission:jobs.view');
        Route::get('/jobs/pending', AdminJobPending::class)->name('jobs.pending')->middleware('admin.permission:jobs.approve');
        Route::get('/jobs/bin', AdminJobBin::class)->name('jobs.bin')->middleware('admin.permission:jobs.delete');
        Route::get('/jobs/create', AdminJobCreate::class)->name('jobs.create')->middleware('admin.permission:jobs.create');
        Route::get('/jobs/{job}', AdminJobShow::class)->name('jobs.show')->middleware('admin.permission:jobs.view');
        Route::get('/jobs/{job}/edit', AdminJobEdit::class)->name('jobs.edit')->middleware('admin.permission:jobs.edit');

        // Applications
        Route::get('/applications', AdminApplicationIndex::class)->name('applications.index')->middleware('admin.permission:applications.view');
        Route::get('/applications/bin', AdminApplicationBin::class)->name('applications.bin')->middleware('admin.permission:applications.delete');
        Route::get('/applications/{application}', AdminApplicationShow::class)->name('applications.show')->middleware('admin.permission:applications.view');

        // Plans
        Route::get('/plans/job-seeker', JobSeekerPlanIndex::class)->name('plans.jobseeker.index')->middleware('admin.permission:plans.view');
        Route::get('/plans/job-seeker/create', JobSeekerPlanForm::class)->name('plans.jobseeker.create')->middleware('admin.permission:plans.manage');
        Route::get('/plans/job-seeker/{plan}/edit', JobSeekerPlanForm::class)->name('plans.jobseeker.edit')->middleware('admin.permission:plans.manage');
        Route::get('/plans/recruiter', RecruiterPlanIndex::class)->name('plans.recruiter.index')->middleware('admin.permission:plans.view');
        Route::get('/plans/recruiter/create', RecruiterPlanForm::class)->name('plans.recruiter.create')->middleware('admin.permission:plans.manage');
        Route::get('/plans/recruiter/{plan}/edit', RecruiterPlanForm::class)->name('plans.recruiter.edit')->middleware('admin.permission:plans.manage');
        Route::get('/plans/featured', FeaturedPlanIndex::class)->name('plans.featured.index')->middleware('admin.permission:plans.view');
        Route::get('/plans/assign', AssignPlan::class)->name('plans.assign.index')->middleware('admin.permission:subscriptions.assign');

        // Payments & Subscription Overview
        Route::get('/payments', \App\Livewire\Admin\Payments\PaymentIndex::class)->name('payments.index')->middleware('admin.permission:payments.view');
        Route::get('/subscriptions/job-seekers', \App\Livewire\Admin\Subscriptions\JobSeekerSubscriptions::class)->name('subscriptions.jobseekers')->middleware('admin.permission:subscriptions.view');
        Route::get('/subscriptions/recruiters', \App\Livewire\Admin\Subscriptions\RecruiterSubscriptions::class)->name('subscriptions.recruiters')->middleware('admin.permission:subscriptions.view');

        // User Profile (admin view/edit)
        Route::get('/users/{user}/profile', \App\Livewire\Admin\Users\Profile\ProfileShow::class)->name('users.profile.show');
        Route::get('/users/{user}/profile/edit', \App\Livewire\Admin\Users\Profile\ProfileEdit::class)->name('users.profile.edit');

        // News Categories
        Route::get('/news-categories', \App\Livewire\Admin\News\NewsCategoryIndex::class)->name('news-categories.index')->middleware('admin.permission:news.view');
        Route::get('/news-categories/create', \App\Livewire\Admin\News\NewsCategoryForm::class)->name('news-categories.create')->middleware('admin.permission:news.create');
        Route::get('/news-categories/{category}/edit', \App\Livewire\Admin\News\NewsCategoryForm::class)->name('news-categories.edit')->middleware('admin.permission:news.edit');

        // News
        Route::get('/news', \App\Livewire\Admin\News\NewsIndex::class)->name('news.index')->middleware('admin.permission:news.view');
        Route::get('/news/create', \App\Livewire\Admin\News\NewsForm::class)->name('news.create')->middleware('admin.permission:news.create');
        Route::get('/news/{news}/edit', \App\Livewire\Admin\News\NewsForm::class)->name('news.edit')->middleware('admin.permission:news.edit');

        // Event Categories
        Route::get('/event-categories', \App\Livewire\Admin\Events\EventCategoryIndex::class)->name('event-categories.index')->middleware('admin.permission:events.view');
        Route::get('/event-categories/create', \App\Livewire\Admin\Events\EventCategoryForm::class)->name('event-categories.create')->middleware('admin.permission:events.create');
        Route::get('/event-categories/{category}/edit', \App\Livewire\Admin\Events\EventCategoryForm::class)->name('event-categories.edit')->middleware('admin.permission:events.edit');

        // Events
        Route::get('/events', \App\Livewire\Admin\Events\EventIndex::class)->name('events.index')->middleware('admin.permission:events.view');
        Route::get('/events/create', \App\Livewire\Admin\Events\EventForm::class)->name('events.create')->middleware('admin.permission:events.create');
        Route::get('/events/{event}/edit', \App\Livewire\Admin\Events\EventForm::class)->name('events.edit')->middleware('admin.permission:events.edit');

        // Blog Categories
        Route::get('/blog-categories', \App\Livewire\Admin\Blogs\BlogCategoryIndex::class)->name('blog-categories.index')->middleware('admin.permission:blogs.view');
        Route::get('/blog-categories/create', \App\Livewire\Admin\Blogs\BlogCategoryForm::class)->name('blog-categories.create')->middleware('admin.permission:blogs.create');
        Route::get('/blog-categories/{category}/edit', \App\Livewire\Admin\Blogs\BlogCategoryForm::class)->name('blog-categories.edit')->middleware('admin.permission:blogs.edit');

        // Blogs
        Route::get('/blogs', \App\Livewire\Admin\Blogs\BlogIndex::class)->name('blogs.index')->middleware('admin.permission:blogs.view');
        Route::get('/blogs/create', \App\Livewire\Admin\Blogs\BlogForm::class)->name('blogs.create')->middleware('admin.permission:blogs.create');
        Route::get('/blogs/{blog}/edit', \App\Livewire\Admin\Blogs\BlogForm::class)->name('blogs.edit')->middleware('admin.permission:blogs.edit');

        // Specialties
        Route::get('/specialties', \App\Livewire\Admin\Specialties\SpecialtyIndex::class)->name('specialties.index')->middleware('admin.permission:specialties.manage');

        // FAQs
        Route::get('/faqs', \App\Livewire\Admin\Faqs\FaqManager::class)->name('faqs.index')->middleware('admin.permission:faqs.manage');

        // Feedback
        Route::get('/feedbacks', \App\Livewire\Admin\Feedback\FeedbackIndex::class)->name('feedbacks.index')->middleware('admin.permission:feedbacks.view');

        // Support Tickets
        Route::get('/support-tickets', \App\Livewire\Admin\Support\TicketIndex::class)->name('support-tickets.index')->middleware('admin.permission:support_tickets.view');
        Route::get('/support-tickets/{ticketId}', \App\Livewire\Admin\Support\TicketShow::class)->name('support-tickets.show')->middleware('admin.permission:support_tickets.view');

        // Roles
        Route::get('/roles', \App\Livewire\Admin\Roles\RoleIndex::class)->name('roles.index')->middleware('admin.permission:roles.view');
        Route::get('/roles/create', \App\Livewire\Admin\Roles\RoleForm::class)->name('roles.create')->middleware('admin.permission:roles.manage');
        Route::get('/roles/{role}/edit', \App\Livewire\Admin\Roles\RoleForm::class)->name('roles.edit')->middleware('admin.permission:roles.manage');

        // Sub-Admins
        Route::get('/sub-admins', \App\Livewire\Admin\SubAdmins\SubAdminIndex::class)->name('sub-admins.index')->middleware('admin.permission:sub_admins.view');
        Route::get('/sub-admins/create', \App\Livewire\Admin\SubAdmins\SubAdminForm::class)->name('sub-admins.create')->middleware('admin.permission:sub_admins.manage');
        Route::get('/sub-admins/{admin}/edit', \App\Livewire\Admin\SubAdmins\SubAdminForm::class)->name('sub-admins.edit')->middleware('admin.permission:sub_admins.manage');

        // Site Settings
        Route::get('/settings', \App\Livewire\Admin\Settings\SiteSettings::class)->name('settings')->middleware('admin.permission:settings.view');
    });

});

/*
|--------------------------------------------------------------------------
| Recruiter Routes
|--------------------------------------------------------------------------
*/
Route::prefix('recruiter')->name('recruiter.')->group(function () {

    // Auth
    Route::get('/register', RecruiterRegistration::class)->name('register');
    Route::get('/login', \App\Livewire\Frontend\Auth\RecruiterLogin::class)->name('login');
    Route::get('/google/onboarding', RecruiterGoogleOnboarding::class)->name('google.onboarding');
    Route::post('/logout', function () {
        auth()->logout();
        return redirect('/');
    })->name('logout');

    // Onboarding (auth required but no profile check)
    Route::middleware('auth')->group(function () {
        Route::get('/onboarding', \App\Livewire\Recruiter\Onboarding\RecruiterOnboarding::class)->name('onboarding');
    });

    // Authenticated recruiter routes (profile must be completed)
    Route::middleware(['auth', 'recruiter.profile'])->group(function () {
        Route::get('/dashboard', \App\Livewire\Recruiter\Dashboard\RecruiterDashboard::class)->name('dashboard');
        Route::get('/jobs', RecruiterJobIndex::class)->name('jobs.index');
        Route::get('/jobs/create', RecruiterJobCreate::class)->name('jobs.create');
        Route::get('/jobs/{job}', RecruiterJobShow::class)->name('jobs.show');
        Route::get('/jobs/{job}/edit', RecruiterJobEdit::class)->name('jobs.edit');

        // Applications
        Route::get('/applications', RecruiterApplicationIndex::class)->name('applications.index');
        Route::get('/applications/{application}', RecruiterApplicationShow::class)->name('applications.show');
        Route::get('/jobs/{job}/applications', RecruiterApplicationForJob::class)->name('applications.for-job');

        // Plans & Subscriptions
        Route::get('/plans', \App\Livewire\Recruiter\Plans\PlanIndex::class)->name('plans.index');
        Route::get('/my-plan', \App\Livewire\Recruiter\Plans\ActivePlan::class)->name('plan');
        Route::get('/checkout/{planOption}', \App\Livewire\Recruiter\Plans\RecruiterCheckout::class)->name('checkout.show');

        // Feature a job
        Route::get('/jobs/{job}/feature', \App\Livewire\Recruiter\Jobs\FeatureJob::class)->name('jobs.feature');

        // Candidates
        Route::get('/candidates/{userId}', \App\Livewire\Recruiter\Candidates\CandidateShow::class)->name('candidates.show');

        // Settings
        Route::get('/settings', \App\Livewire\Recruiter\Settings\RecruiterSettings::class)->name('settings');

        // Feedback & Support
        Route::get('/feedback', \App\Livewire\Shared\FeedbackForm::class)->name('feedback');
        Route::get('/support', \App\Livewire\Shared\TicketForm::class)->name('support');
    });
});

/*
|--------------------------------------------------------------------------
| Google OAuth
|--------------------------------------------------------------------------
*/
Route::get('/auth/google/recruiter/redirect', [RecruiterGoogleController::class, 'redirect'])->name('recruiter.google.redirect');
Route::get('/auth/google/jobseeker/redirect', [JobSeekerGoogleController::class, 'redirect'])->name('jobseeker.google.redirect');
Route::get('/auth/google/callback', GoogleCallbackController::class)->name('google.callback');

/*
|--------------------------------------------------------------------------
| Job Seeker Routes
|--------------------------------------------------------------------------
*/

// Auth (guests only)
Route::middleware('guest')->group(function () {
    Route::get('/login', \App\Livewire\Frontend\Auth\JobSeekerLogin::class)->name('jobseeker.login');
    Route::get('/register', \App\Livewire\Frontend\Auth\JobSeekerRegister::class)->name('jobseeker.register');
});

Route::middleware('auth')->post('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('jobseeker.logout');

// Authenticated
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', \App\Livewire\JobSeeker\Dashboard\JobSeekerDashboard::class)->name('jobseeker.dashboard');
});

Route::middleware('auth')->prefix('my-applications')->name('jobseeker.applications.')->group(function () {
    Route::get('/', JobSeekerApplicationIndex::class)->name('index');
    Route::get('/{application}', JobSeekerApplicationShow::class)->name('show');
});

Route::middleware('auth')->group(function () {
    Route::get('/saved-jobs', SavedJobIndex::class)->name('jobseeker.saved-jobs.index');
});

// Job Seeker Profile
Route::middleware('auth')->prefix('profile')->name('profile.')->group(function () {
    Route::get('/', \App\Livewire\JobSeeker\Profile\ProfileShow::class)->name('show');
    Route::get('/edit', \App\Livewire\JobSeeker\Profile\ProfileEdit::class)->name('edit');
});

// AI Tools (Job Seekers)
Route::middleware('auth')->prefix('ai')->name('jobseeker.ai.')->group(function () {
    Route::get('/resume-builder', \App\Livewire\JobSeeker\AI\ResumeBuilder::class)->name('resume-builder');
});

// Feedback & Support (Job Seekers)
Route::middleware('auth')->group(function () {
    Route::get('/feedback', \App\Livewire\Shared\FeedbackForm::class)->name('jobseeker.feedback');
    Route::get('/support', \App\Livewire\Shared\TicketForm::class)->name('jobseeker.support');
});

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/best-healthcare-jobs', PublicJobIndex::class)->name('jobs.index');
Route::get('/best-healthcare-jobs/{job}', JobDetail::class)->name('jobs.show');
Route::get('/profile/{slug}', PublicProfile::class)->name('profile.public');
Route::get('/institution/{institution}', \App\Livewire\Frontend\Institutions\InstitutionShow::class)->name('institution.show');
Route::get('/plans', \App\Livewire\Frontend\Plans\PlanIndex::class)->name('plans.index');
Route::get('/blogs', \App\Livewire\Frontend\Blogs\BlogIndex::class)->name('blogs.index');
Route::get('/blogs/{slug}', \App\Livewire\Frontend\Blogs\BlogShow::class)->name('blogs.show');

Route::middleware('auth')->group(function () {
    Route::get('/my-plan', \App\Livewire\JobSeeker\Plan\ActivePlan::class)->name('jobseeker.plan');
    Route::get('/checkout/{planOption}', \App\Livewire\Frontend\Plans\Checkout::class)->name('checkout.show');
});

// Static Pages
Route::view('/about-vaidyog-healthcare-job-site', 'pages.about')->name('about');
Route::view('/terms', 'pages.terms')->name('terms');
Route::view('/privacy', 'pages.privacy')->name('privacy');
Route::view('/disclaimer', 'pages.disclaimer')->name('disclaimer');

// Sitemap
Route::get('/sitemap.xml', [\App\Http\Controllers\SitemapController::class, 'index'])->name('sitemap.index');
Route::get('/sitemap-static.xml', [\App\Http\Controllers\SitemapController::class, 'static'])->name('sitemap.static');
Route::get('/sitemap-jobs.xml', [\App\Http\Controllers\SitemapController::class, 'jobs'])->name('sitemap.jobs');
Route::get('/sitemap-blogs.xml', [\App\Http\Controllers\SitemapController::class, 'blogs'])->name('sitemap.blogs');

// Razorpay Webhook (no CSRF)
Route::post('/razorpay/webhook', [\App\Http\Controllers\Webhook\RazorpayWebhookController::class, 'handle'])->name('razorpay.webhook');
