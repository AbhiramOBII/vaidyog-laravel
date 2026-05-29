<?php

namespace App\Traits;

use App\Models\JobSeekerProfile;
use App\Services\Profile\ProfileCompletenessService;

trait RecalculatesProfileCompleteness
{
    public function recalculateCompleteness(string $userId): void
    {
        $profile = JobSeekerProfile::where('user_id', $userId)->first();
        if (!$profile) return;

        $service = app(ProfileCompletenessService::class);
        $score = $service->calculate($profile);
        $profile->update(['profile_completeness' => $score]);
    }
}
