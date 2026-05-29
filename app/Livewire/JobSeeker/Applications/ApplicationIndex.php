<?php

namespace App\Livewire\JobSeeker\Applications;

use App\Enums\ApplicationStatusEnum;
use App\Models\JobApplication;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app', ['pageTitle' => 'My Applications'])]
class ApplicationIndex extends Component
{
    use WithPagination;

    #[Url] public string $search = '';
    #[Url] public string $status = '';

    public function updatedSearch(): void { $this->resetPage(); }
    public function updatedStatus(): void { $this->resetPage(); }

    public function render()
    {
        $userId = auth()->id();
        $query = JobApplication::forApplicant($userId)->with(['job.recruiter']);

        if ($this->search) {
            $query->whereHas('job', fn($q) => $q->where('job_title', 'like', "%{$this->search}%")
                ->orWhere('institution_name', 'like', "%{$this->search}%"));
        }
        if ($this->status) {
            $query->byStatus($this->status);
        }

        $baseQuery = JobApplication::forApplicant($userId);

        return view('livewire.job-seeker.applications.application-index', [
            'applications' => $query->latest('applied_at')->paginate(12),
            'statuses' => ApplicationStatusEnum::cases(),
            'stats' => [
                'total' => (clone $baseQuery)->count(),
                'underReview' => (clone $baseQuery)->whereIn('status', ['applied', 'reviewed', 'shortlisted'])->count(),
                'interviews' => (clone $baseQuery)->whereIn('status', ['interviewed', 'scheduled'])->count(),
                'offers' => (clone $baseQuery)->byStatus('offered')->count(),
            ],
        ]);
    }
}
