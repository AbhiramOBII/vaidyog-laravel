<?php

namespace App\Livewire\JobSeeker\Profile;

use App\Services\Subscription\SubscriptionService;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app', ['pageTitle' => 'My Profile'])]
class ProfileShow extends Component
{
    public function render()
    {
        $user = auth()->user();
        $profile = $user->jobSeekerProfile;

        $incompleteSections = [];
        if ($profile) {
            if (!$profile->first_name || !$profile->last_name) $incompleteSections[] = 'Personal Info';
            if (!$profile->designation) $incompleteSections[] = 'Designation';
            if (!$profile->key_skills || count($profile->key_skills) < 3) $incompleteSections[] = 'Skills';
            if (!$profile->profile_photo_path) $incompleteSections[] = 'Profile Picture';
            if (!$profile->resume_path) $incompleteSections[] = 'Resume';
            if ($profile->languages()->count() === 0) $incompleteSections[] = 'Languages';
            if ($profile->educations()->count() === 0) $incompleteSections[] = 'Education';
            if ($profile->employments()->count() === 0) $incompleteSections[] = 'Work Experience';
            if ($profile->certifications()->count() === 0) $incompleteSections[] = 'Certifications';
        }

        $subscriptionService = app(SubscriptionService::class);
        $activeSub = $subscriptionService->getActivePlanForJobSeeker($user);
        $remaining = $subscriptionService->getRemainingApplicationsThisMonth($user);
        $recentPayments = $user->payments()->latest()->limit(3)->get();

        return view('livewire.job-seeker.profile.profile-show', compact(
            'profile', 'incompleteSections', 'activeSub', 'remaining', 'recentPayments'
        ));
    }
}
