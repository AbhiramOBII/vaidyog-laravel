<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserStatusEnum;
use App\Http\Controllers\Controller;
use App\Models\MedicalInstitution;
use App\Models\User;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $jobSeekerBase = User::where('user_type', 'user');
        $recruiterBase = MedicalInstitution::query();

        return view('admin.dashboard', [
            'pageTitle' => 'Dashboard',
            'jobSeekers' => [
                'total' => (clone $jobSeekerBase)->count(),
                'active' => (clone $jobSeekerBase)->where('status', UserStatusEnum::Active)->count(),
                'pending' => (clone $jobSeekerBase)->where('status', UserStatusEnum::PendingVerification)->count(),
                'blocked' => (clone $jobSeekerBase)->where('status', UserStatusEnum::Blocked)->count(),
            ],
            'recruiters' => [
                'total' => (clone $recruiterBase)->count(),
                'active' => (clone $recruiterBase)->where('status', UserStatusEnum::Active)->count(),
                'pending' => (clone $recruiterBase)->where('status', UserStatusEnum::PendingVerification)->count(),
                'featured' => (clone $recruiterBase)->featured()->count(),
            ],
        ]);
    }
}
