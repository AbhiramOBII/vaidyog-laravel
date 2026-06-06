<?php

namespace App\Livewire\Frontend\Profile;

use App\Models\JobSeekerProfile;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.public')]
class PublicProfile extends Component
{
    public JobSeekerProfile $profile;

    public function mount(string $slug): void
    {
        $this->profile = JobSeekerProfile::where('profile_slug', $slug)
            ->with([
                'user',
                'specialty',
                'certifications',
                'educations',
                'employments',
                'languages',
            ])
            ->firstOrFail();

        // Only show active user profiles
        if (!$this->profile->user || !$this->profile->user->is_active) {
            abort(404);
        }
    }

    public function render()
    {
        return view('livewire.frontend.profile.public-profile');
    }
}
