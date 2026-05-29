<?php

namespace App\Livewire\Admin\Applications;

use App\Models\JobApplication;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.admin', ['pageTitle' => 'Application Bin'])]
class ApplicationBin extends Component
{
    use WithPagination;

    public function restore(string $id): void
    {
        $application = JobApplication::onlyTrashed()->findOrFail($id);
        $application->restore();
        $application->updateStatus('applied');
        session()->flash('message', 'Application restored.');
    }

    public function render()
    {
        return view('livewire.admin.applications.application-bin', [
            'applications' => JobApplication::onlyTrashed()
                ->with(['job', 'applicant', 'recruiter'])
                ->latest('deleted_at')
                ->paginate(20),
        ]);
    }
}
