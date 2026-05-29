<?php

namespace App\Livewire\JobSeeker\SavedJobs;

use App\Models\SavedJob;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app', ['pageTitle' => 'Saved Jobs'])]
class SavedJobIndex extends Component
{
    use WithPagination;

    public function unsave(int $id): void
    {
        SavedJob::where('id', $id)->where('user_id', auth()->id())->delete();
    }

    public function render()
    {
        return view('livewire.job-seeker.saved-jobs.saved-job-index', [
            'savedJobs' => SavedJob::where('user_id', auth()->id())
                ->with('job.recruiter.profile')
                ->latest('created_at')
                ->paginate(12),
        ]);
    }
}
