<?php

namespace App\Policies;

use App\Models\JobApplication;
use App\Models\MedicalInstitution;
use App\Models\User;

class JobApplicationPolicy
{
    public function viewAny(User|MedicalInstitution $user): bool
    {
        return true;
    }

    public function view(User|MedicalInstitution $user, JobApplication $application): bool
    {
        // Admin can view all (handled at route level)
        // Recruiter who owns the job
        if ($user instanceof MedicalInstitution) {
            return $application->isOwnedByRecruiter($user->id);
        }

        // Job seeker who is the applicant
        return $application->applicant_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->user_type === 'user';
    }

    public function updateStatus(User|MedicalInstitution $user, JobApplication $application): bool
    {
        if ($user instanceof MedicalInstitution) {
            return $application->isOwnedByRecruiter($user->id);
        }
        return false;
    }

    public function delete(User|MedicalInstitution $user, JobApplication $application): bool
    {
        if ($user instanceof MedicalInstitution) {
            return $application->isOwnedByRecruiter($user->id);
        }
        // Job seeker can withdraw only if status is applied or reviewed
        if ($application->applicant_id === $user->id) {
            return in_array($application->status->value, ['applied', 'reviewed']);
        }
        return false;
    }

    public function restore(User|MedicalInstitution $user, JobApplication $application): bool
    {
        // Admin only — handled at route/middleware level
        return false;
    }
}
