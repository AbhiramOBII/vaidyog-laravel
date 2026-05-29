<?php

namespace App\Services\Profile;

use App\Models\JobSeekerProfile;

class ProfileCompletenessService
{
    public function calculate(JobSeekerProfile $profile): int
    {
        $score = 0;
        $profile->loadMissing([
            'languages', 'certifications', 'educations', 'employments', 'projects',
            'publications', 'presentations', 'researches', 'honoursAwards', 'affiliations',
        ]);

        // Personal Info (7)
        if ($profile->first_name) $score += 4;
        if ($profile->last_name) $score += 4;
        if ($profile->date_of_birth) $score += 4;
        if ($profile->phone) $score += 4;
        if ($profile->city) $score += 4;
        if ($profile->state) $score += 4;
        if ($profile->about && strlen($profile->about) >= 50) $score += 4;

        // Professional Info (4)
        if ($profile->designation) $score += 4;
        if ($profile->subdesignation) $score += 4;
        if (is_array($profile->key_skills) && count($profile->key_skills) >= 3) $score += 4;
        // is_open_to_work always counts once profile exists
        $score += 4;

        // Profile Assets (2)
        if ($profile->profile_photo_path) $score += 4;
        if ($profile->resume_path) $score += 4;

        // Sub-models (10)
        if ($profile->languages->isNotEmpty()) $score += 4;
        if ($profile->certifications->isNotEmpty()) $score += 4;
        if ($profile->educations->isNotEmpty()) $score += 4;
        if ($profile->employments->isNotEmpty()) $score += 4;
        if ($profile->projects->isNotEmpty()) $score += 4;
        if ($profile->publications->isNotEmpty()) $score += 4;
        if ($profile->presentations->isNotEmpty()) $score += 4;
        if ($profile->researches->isNotEmpty()) $score += 4;
        if ($profile->honoursAwards->isNotEmpty()) $score += 4;
        if ($profile->affiliations->isNotEmpty()) $score += 4;

        // Contact (2)
        if ($profile->pincode) $score += 4;
        if ($profile->nationality) $score += 4;

        return min($score, 100);
    }
}
