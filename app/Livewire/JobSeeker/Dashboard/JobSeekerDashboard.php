<?php

namespace App\Livewire\JobSeeker\Dashboard;

use App\Models\Event;
use App\Models\JobPosting;
use App\Models\News;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app', ['pageTitle' => 'Dashboard'])]
class JobSeekerDashboard extends Component
{
    public function render()
    {
        $user = auth()->user();
        $profile = $user->jobSeekerProfile;

        $recentJobs = JobPosting::publiclyVisible()
            ->with(['recruiter.profile', 'specialty'])
            ->latest()
            ->limit(6)
            ->get();

        $latestNews = News::published()
            ->latest('published_at')
            ->limit(4)
            ->get();

        $upcomingEvents = Event::published()
            ->upcoming()
            ->orderBy('event_date')
            ->limit(4)
            ->get();

        $applicationCount = $user->applications()->count();
        $savedJobCount = $user->savedJobs()->count();

        return view('livewire.job-seeker.dashboard.job-seeker-dashboard', compact(
            'profile',
            'recentJobs',
            'latestNews',
            'upcomingEvents',
            'applicationCount',
            'savedJobCount',
        ));
    }
}
