<?php

namespace App\Livewire\Recruiter\Applications;

use App\Enums\ApplicationRankingEnum;
use App\Enums\ApplicationStatusEnum;
use App\Models\JobApplication;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.recruiter', ['pageTitle' => 'All Applicants'])]
class ApplicationIndex extends Component
{
    use WithPagination;

    #[Url] public string $search = '';
    #[Url] public string $status = '';
    #[Url] public string $ranking = '';
    #[Url] public string $jobFilter = '';

    public function updatedSearch(): void { $this->resetPage(); }
    public function updatedStatus(): void { $this->resetPage(); }
    public function updatedRanking(): void { $this->resetPage(); }
    public function updatedJobFilter(): void { $this->resetPage(); }

    public function render()
    {
        $recruiterId = auth()->id();
        $query = JobApplication::forRecruiter($recruiterId)->with(['job', 'applicant']);

        if ($this->search) {
            $query->where(function ($q) {
                $q->whereHas('applicant', fn($sq) => $sq->where('name', 'like', "%{$this->search}%"))
                  ->orWhereHas('job', fn($sq) => $sq->where('job_title', 'like', "%{$this->search}%"));
            });
        }
        if ($this->status) {
            $query->byStatus($this->status);
        }
        if ($this->ranking) {
            $query->byRanking($this->ranking);
        }
        if ($this->jobFilter) {
            $query->forJob($this->jobFilter);
        }

        $baseQuery = JobApplication::forRecruiter($recruiterId);

        return view('livewire.recruiter.applications.application-index', [
            'applications' => $query->orderedByRanking()->latest('applied_at')->paginate(20),
            'statuses' => ApplicationStatusEnum::cases(),
            'rankings' => ApplicationRankingEnum::cases(),
            'stats' => [
                'total' => (clone $baseQuery)->count(),
                'new' => (clone $baseQuery)->byStatus('applied')->count(),
                'shortlisted' => (clone $baseQuery)->byStatus('shortlisted')->count(),
                'interviewed' => (clone $baseQuery)->byStatus('interviewed')->count(),
                'offered' => (clone $baseQuery)->byStatus('offered')->count(),
                'rejected' => (clone $baseQuery)->byStatus('rejected')->count(),
            ],
        ]);
    }
}
