<?php

namespace App\Livewire\Recruiter\Applications;

use App\Models\JobApplication;
use App\Services\Application\ApplicationService;
use App\Services\Application\StatusTransitionService;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.recruiter', ['pageTitle' => 'Application Detail'])]
class ApplicationShow extends Component
{
    public JobApplication $application;
    public string $newStatus = '';
    public string $recruiterNotes = '';

    public function mount(JobApplication $application): void
    {
        if (!$application->isOwnedByRecruiter(auth()->id())) {
            abort(403);
        }
        $this->application = $application->load(['job', 'applicant.jobSeekerProfile']);
        $this->recruiterNotes = $application->recruiter_notes ?? '';
    }

    public function updateStatus(): void
    {
        if (!$this->newStatus) return;

        try {
            app(ApplicationService::class)->updateStatus($this->application, $this->newStatus, $this->recruiterNotes ?: null);
            $this->application->refresh();
            $this->newStatus = '';
            session()->flash('message', 'Status updated.');
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function saveNotes(): void
    {
        $this->application->update(['recruiter_notes' => $this->recruiterNotes]);
        session()->flash('message', 'Notes saved.');
    }

    public function render()
    {
        return view('livewire.recruiter.applications.application-show', [
            'allowedStatuses' => app(StatusTransitionService::class)->getAllowedNextStatuses($this->application->status->value),
        ]);
    }
}
