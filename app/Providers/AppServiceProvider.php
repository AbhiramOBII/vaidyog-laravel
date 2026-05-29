<?php

namespace App\Providers;

use App\Models\JobApplication;
use App\Models\JobPosting;
use App\Models\JobSeekerProfile;
use App\Models\MedicalInstitutionProfile;
use App\Models\UserAffiliation;
use App\Models\UserCertification;
use App\Models\UserEducation;
use App\Models\UserEmployment;
use App\Models\UserHonoursAward;
use App\Models\UserLanguage;
use App\Models\UserPresentation;
use App\Models\UserProject;
use App\Models\UserPublication;
use App\Models\UserResearch;
use App\Observers\JobApplicationObserver;
use App\Observers\JobPostingObserver;
use App\Observers\JobSeekerProfileObserver;
use App\Observers\MedicalInstitutionProfileObserver;
use App\Observers\SubModelObserver;
use App\Observers\UserEmploymentObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        MedicalInstitutionProfile::observe(MedicalInstitutionProfileObserver::class);
        JobPosting::observe(JobPostingObserver::class);
        JobApplication::observe(JobApplicationObserver::class);
        JobSeekerProfile::observe(JobSeekerProfileObserver::class);
        UserEmployment::observe(UserEmploymentObserver::class);

        $subModels = [
            UserLanguage::class, UserCertification::class, UserEducation::class,
            UserEmployment::class, UserProject::class, UserPublication::class,
            UserPresentation::class, UserResearch::class, UserHonoursAward::class,
            UserAffiliation::class,
        ];
        foreach ($subModels as $model) {
            $model::observe(SubModelObserver::class);
        }
    }
}
