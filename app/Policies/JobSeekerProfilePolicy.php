<?php

namespace App\Policies;

use App\Models\JobSeekerProfile;
use App\Models\User;

class JobSeekerProfilePolicy
{
    public function view(User $user, JobSeekerProfile $profile): bool
    {
        // Admin, owning user, or any authenticated recruiter
        if ($user->user_type === 'admin') return true;
        if ($user->id === $profile->user_id) return true;
        if ($user->user_type === 'MedicalInstitution') return true;
        return false;
    }

    public function update(User $user, JobSeekerProfile $profile): bool
    {
        if ($user->user_type === 'admin') return true;
        return $user->id === $profile->user_id;
    }

    public function uploadPicture(User $user, JobSeekerProfile $profile): bool
    {
        return $user->id === $profile->user_id;
    }

    public function uploadResume(User $user, JobSeekerProfile $profile): bool
    {
        return $user->id === $profile->user_id;
    }
}
