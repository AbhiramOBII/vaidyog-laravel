<?php

namespace App\Policies;

use App\Models\JobPosting;
use App\Models\User;

class JobPostingPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->user_type, ['admin', 'subadmin', 'MedicalInstitution']);
    }

    public function view(User $user, JobPosting $job): bool
    {
        if (in_array($user->user_type, ['admin', 'subadmin'])) return true;
        if ($user->user_type === 'MedicalInstitution') return $job->recruiter_id === $user->id;
        return true; // job seekers can view
    }

    public function create(User $user): bool
    {
        return in_array($user->user_type, ['admin', 'subadmin', 'MedicalInstitution']);
    }

    public function update(User $user, JobPosting $job): bool
    {
        if (in_array($user->user_type, ['admin', 'subadmin'])) return true;
        if ($user->user_type === 'MedicalInstitution') {
            return $job->recruiter_id === $user->id;
        }
        return false;
    }

    public function delete(User $user, JobPosting $job): bool
    {
        if (in_array($user->user_type, ['admin', 'subadmin'])) return true;
        return $job->recruiter_id === $user->id;
    }

    public function approve(User $user): bool
    {
        return in_array($user->user_type, ['admin', 'subadmin']);
    }

    public function reject(User $user): bool
    {
        return in_array($user->user_type, ['admin', 'subadmin']);
    }

    public function feature(User $user): bool
    {
        return in_array($user->user_type, ['admin', 'subadmin']);
    }

    public function restore(User $user): bool
    {
        return in_array($user->user_type, ['admin', 'subadmin']);
    }
}
