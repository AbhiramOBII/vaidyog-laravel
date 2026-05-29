<?php

namespace App\Livewire\Recruiter\Candidates;

use App\Models\JobSeekerProfile;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.recruiter', ['pageTitle' => 'Candidate Profile'])]
class CandidateShow extends Component
{
    public int $userId;
    public ?JobSeekerProfile $profile = null;

    public function mount(int $userId): void
    {
        $this->userId = $userId;
        $candidate = User::findOrFail($userId);
        $this->profile = $candidate->jobSeekerProfile;

        if (!$this->profile || !$this->profile->is_open_to_work) {
            abort(403, 'This profile is not available.');
        }
    }

    public function render()
    {
        $user = User::find($this->userId);
        $languages = $user->languages;
        $certifications = $user->certifications;
        $educations = $user->educations;
        $employments = $user->employments;
        $projects = $user->projects;

        return view('livewire.recruiter.candidates.candidate-show', compact(
            'languages', 'certifications', 'educations', 'employments', 'projects'
        ));
    }
}
