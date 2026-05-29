<?php

namespace App\Livewire\Frontend\Institutions;

use App\Models\JobPosting;
use App\Models\MedicalInstitutionProfile;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.public')]
class InstitutionShow extends Component
{
    public MedicalInstitutionProfile $profile;

    public function mount(MedicalInstitutionProfile $institution): void
    {
        $recruiter = $institution->user;

        if (!$recruiter || !$recruiter->is_active || $recruiter->status->value !== 'active') {
            abort(404);
        }

        $this->profile = $institution;
    }

    #[Computed]
    public function activeJobs()
    {
        return JobPosting::publiclyVisible()
            ->where('recruiter_id', $this->profile->user_id)
            ->latest('approved_at')
            ->take(10)
            ->get();
    }

    #[Computed]
    public function totalJobs(): int
    {
        return JobPosting::publiclyVisible()
            ->where('recruiter_id', $this->profile->user_id)
            ->count();
    }

    public function render()
    {
        return view('livewire.frontend.institutions.institution-show')
            ->title($this->profile->institution_name . ' — Healthcare Jobs | Vaidyog');
    }
}
