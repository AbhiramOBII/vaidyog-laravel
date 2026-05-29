<?php

namespace App\Livewire\JobSeeker\Applications;

use App\Models\JobApplication;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app', ['pageTitle' => 'Application Detail'])]
class ApplicationShow extends Component
{
    public JobApplication $application;

    public function mount(JobApplication $application): void
    {
        if ($application->applicant_id !== auth()->id()) {
            abort(403);
        }
        $this->application = $application->load(['job']);
    }

    public function withdraw(): void
    {
        if (!in_array($this->application->status->value, ['applied', 'reviewed'])) {
            session()->flash('error', 'You can only withdraw applications that are in Applied or Reviewed status.');
            return;
        }

        $this->application->delete();
        session()->flash('message', 'Application withdrawn.');
        $this->redirect(route('jobseeker.applications.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.job-seeker.applications.application-show');
    }
}
