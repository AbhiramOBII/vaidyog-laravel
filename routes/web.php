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
use App\Livewire\Admin\JobSeekers\JobSeekerIndex;
use App\Livewire\Admin\Recruiters\RecruiterCreate;
use App\Livewire\Admin\Recruiters\RecruiterEdit;
use App\Livewire\Admin\Recruiters\RecruiterIndex;
use App\Livewire\Admin\Recruiters\RecruiterShow;
use App\Livewire\Frontend\Auth\RecruiterGoogleOnboarding;
use App\Livewire\Frontend\Auth\RecruiterRegistration;
use App\Livewire\Frontend\Jobs\JobDetail;
use App\Livewire\Frontend\Jobs\JobIndex as PublicJobIndex;
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
        Route::get('/job-seekers', JobSeekerIndex::class)->name('job-seekers.index');
        Route::get('/job-seekers/create', JobSeekerCreate::class)->name('job-seekers.create');
        Route::get('/job-seekers/bulk-import', JobSeekerBulkImport::class)->name('job-seekers.bulk-import');

        // Recruiters
        Route::get('/recruiters', RecruiterIndex::class)->name('recruiters.index');
        Route::get('/recruiters/create', RecruiterCreate::class)->name('recruiters.create');
        Route::get('/recruiters/{user}', RecruiterShow::class)->name('recruiters.show');
        Route::get('/recruiters/{user}/edit', RecruiterEdit::class)->name('recruiters.edit');

        // Job Postings
        Route::get('/jobs', AdminJobIndex::class)->name('jobs.index');
        Route::get('/jobs/pending', AdminJobPending::class)->name('jobs.pending');
        Route::get('/jobs/bin', AdminJobBin::class)->name('jobs.bin');
        Route::get('/jobs/create', AdminJobCreate::class)->name('jobs.create');
        Route::get('/jobs/{job}', AdminJobShow::class)->name('jobs.show');
        Route::get('/jobs/{job}/edit', AdminJobEdit::class)->name('jobs.edit');

        // Applications
        Route::get('/applications', AdminApplicationIndex::class)->name('applications.index');
        Route::get('/applications/bin', AdminApplicationBin::class)->name('applications.bin');
        Route::get('/applications/{application}', AdminApplicationShow::class)->name('applications.show');

        // Plans
        Route::get('/plans/job-seeker', JobSeekerPlanIndex::class)->name('plans.jobseeker.index');
        Route::get('/plans/job-seeker/create', JobSeekerPlanForm::class)->name('plans.jobseeker.create');
        Route::get('/plans/job-seeker/{plan}/edit', JobSeekerPlanForm::class)->name('plans.jobseeker.edit');
        Route::get('/plans/recruiter', RecruiterPlanIndex::class)->name('plans.recruiter.index');
        Route::get('/plans/recruiter/create', RecruiterPlanForm::class)->name('plans.recruiter.create');
        Route::get('/plans/recruiter/{plan}/edit', RecruiterPlanForm::class)->name('plans.recruiter.edit');
        Route::get('/plans/featured', FeaturedPlanIndex::class)->name('plans.featured.index');
        Route::get('/plans/assign', AssignPlan::class)->name('plans.assign.index');

        // Payments & Subscription Overview
        Route::get('/payments', \App\Livewire\Admin\Payments\PaymentIndex::class)->name('payments.index');
        Route::get('/subscriptions/job-seekers', \App\Livewire\Admin\Subscriptions\JobSeekerSubscriptions::class)->name('subscriptions.jobseekers');
        Route::get('/subscriptions/recruiters', \App\Livewire\Admin\Subscriptions\RecruiterSubscriptions::class)->name('subscriptions.recruiters');

        // User Profile (admin view/edit)
        Route::get('/users/{user}/profile', \App\Livewire\Admin\Users\Profile\ProfileShow::class)->name('users.profile.show');
        Route::get('/users/{user}/profile/edit', \App\Livewire\Admin\Users\Profile\ProfileEdit::class)->name('users.profile.edit');

        // News Categories
        Route::get('/news-categories', \App\Livewire\Admin\News\NewsCategoryIndex::class)->name('news-categories.index');
        Route::get('/news-categories/create', \App\Livewire\Admin\News\NewsCategoryForm::class)->name('news-categories.create');
        Route::get('/news-categories/{category}/edit', \App\Livewire\Admin\News\NewsCategoryForm::class)->name('news-categories.edit');

        // News
        Route::get('/news', \App\Livewire\Admin\News\NewsIndex::class)->name('news.index');
        Route::get('/news/create', \App\Livewire\Admin\News\NewsForm::class)->name('news.create');
        Route::get('/news/{news}/edit', \App\Livewire\Admin\News\NewsForm::class)->name('news.edit');

        // Event Categories
        Route::get('/event-categories', \App\Livewire\Admin\Events\EventCategoryIndex::class)->name('event-categories.index');
        Route::get('/event-categories/create', \App\Livewire\Admin\Events\EventCategoryForm::class)->name('event-categories.create');
        Route::get('/event-categories/{category}/edit', \App\Livewire\Admin\Events\EventCategoryForm::class)->name('event-categories.edit');

        // Events
        Route::get('/events', \App\Livewire\Admin\Events\EventIndex::class)->name('events.index');
        Route::get('/events/create', \App\Livewire\Admin\Events\EventForm::class)->name('events.create');
        Route::get('/events/{event}/edit', \App\Livewire\Admin\Events\EventForm::class)->name('events.edit');

        // Blog Categories
        Route::get('/blog-categories', \App\Livewire\Admin\Blogs\BlogCategoryIndex::class)->name('blog-categories.index');
        Route::get('/blog-categories/create', \App\Livewire\Admin\Blogs\BlogCategoryForm::class)->name('blog-categories.create');
        Route::get('/blog-categories/{category}/edit', \App\Livewire\Admin\Blogs\BlogCategoryForm::class)->name('blog-categories.edit');

        // Blogs
        Route::get('/blogs', \App\Livewire\Admin\Blogs\BlogIndex::class)->name('blogs.index');
        Route::get('/blogs/create', \App\Livewire\Admin\Blogs\BlogForm::class)->name('blogs.create');
        Route::get('/blogs/{blog}/edit', \App\Livewire\Admin\Blogs\BlogForm::class)->name('blogs.edit');

        // Specialties
        Route::get('/specialties', \App\Livewire\Admin\Specialties\SpecialtyIndex::class)->name('specialties.index');

        // FAQs
        Route::get('/faqs', \App\Livewire\Admin\Faqs\FaqManager::class)->name('faqs.index');

        // Feedback
        Route::get('/feedbacks', \App\Livewire\Admin\Feedback\FeedbackIndex::class)->name('feedbacks.index');

        // Support Tickets
        Route::get('/support-tickets', \App\Livewire\Admin\Support\TicketIndex::class)->name('support-tickets.index');
        Route::get('/support-tickets/{ticketId}', \App\Livewire\Admin\Support\TicketShow::class)->name('support-tickets.show');

        // Site Settings
        Route::get('/settings', \App\Livewire\Admin\Settings\SiteSettings::class)->name('settings');
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
