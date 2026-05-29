<?php

namespace App\Livewire\Recruiter\Jobs;

use App\Models\JobBin;
use App\Models\JobPosting;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.recruiter', ['pageTitle' => 'Job Details'])]
class JobShow extends Component
{
    public JobPosting $job;

    public function mount(JobPosting $job): void
    {
        if ($job->recruiter_id !== Auth::id()) {
            abort(403);
        }
        $this->job = $job;
    }

    public function toggleActive(): void
    {
        $this->job->update(['is_active' => !$this->job->is_active]);
        $this->job->refresh();
    }

    public function deleteJob(): void
    {
        JobBin::create([
            'job_id' => $this->job->id,
            'deleted_by_type' => 'recruiter',
            'deleted_by_id' => Auth::id(),
            'original_data' => $this->job->toArray(),
        ]);
        $this->job->delete();
        session()->flash('success', 'Job moved to bin.');
        $this->redirect(route('recruiter.jobs.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.recruiter.jobs.job-show');
    }
}
