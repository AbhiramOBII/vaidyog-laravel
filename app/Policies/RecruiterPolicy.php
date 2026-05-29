<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\MedicalInstitution;

class RecruiterPolicy
{
    public function viewAny(Admin $admin): bool
    {
        return true;
    }

    public function view(Admin $admin, MedicalInstitution $recruiter): bool
    {
        return true;
    }

    public function create(Admin $admin): bool
    {
        return true;
    }

    public function update(Admin $admin, MedicalInstitution $recruiter): bool
    {
        return true;
    }

    public function delete(Admin $admin, MedicalInstitution $recruiter): bool
    {
        return true;
    }

    public function changeStatus(Admin $admin, MedicalInstitution $recruiter): bool
    {
        return true;
    }

    public function feature(Admin $admin, MedicalInstitution $recruiter): bool
    {
        return true;
    }

    public function export(Admin $admin): bool
    {
        return true;
    }
}
