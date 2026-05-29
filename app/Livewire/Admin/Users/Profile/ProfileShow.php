<?php

namespace App\Livewire\Admin\Users\Profile;

use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.admin', ['pageTitle' => 'User Profile'])]
class ProfileShow extends Component
{
    public int $userId;

    public function mount(int $user): void
    {
        $this->userId = $user;
    }

    public function render()
    {
        $user = User::findOrFail($this->userId);
        $profile = $user->jobSeekerProfile;
        $languages = $user->languages;
        $certifications = $user->certifications;
        $educations = $user->educations;
        $employments = $user->employments;
        $projects = $user->projects;

        return view('livewire.admin.users.profile.profile-show', compact(
            'user', 'profile', 'languages', 'certifications', 'educations', 'employments', 'projects'
        ));
    }
}
