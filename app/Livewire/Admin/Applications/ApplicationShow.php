<?php

namespace App\Livewire\Admin\Applications;

use App\Models\JobApplication;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.admin', ['pageTitle' => 'Application Detail'])]
class ApplicationShow extends Component
{
    public JobApplication $application;

    public function mount(JobApplication $application): void
    {
        $this->application = $application->load(['job', 'applicant.jobSeekerProfile', 'recruiter']);
    }

    public function render()
    {
        return view('livewire.admin.applications.application-show');
    }
}
