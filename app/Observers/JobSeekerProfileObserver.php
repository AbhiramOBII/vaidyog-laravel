<?php

namespace App\Observers;

use App\Models\JobSeekerProfile;
use App\Services\Profile\ProfileCompletenessService;

class JobSeekerProfileObserver
{
    public function saved(JobSeekerProfile $profile): void
    {
        if ($profile->wasChanged('profile_completeness')) {
            return;
        }

        $service = app(ProfileCompletenessService::class);
        $score = $service->calculate($profile->fresh());
        $profile->updateQuietly(['profile_completeness' => $score]);
    }
}
