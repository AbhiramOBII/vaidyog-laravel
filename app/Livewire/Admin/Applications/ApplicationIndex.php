<?php

namespace App\Livewire\Admin\Applications;

use App\Enums\ApplicationRankingEnum;
use App\Enums\ApplicationStatusEnum;
use App\Models\JobApplication;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.admin', ['pageTitle' => 'Applications'])]
class ApplicationIndex extends Component
{
    use WithPagination;

    #[Url] public string $search = '';
    #[Url] public string $status = '';
    #[Url] public string $ranking = '';

    public function updatedSearch(): void { $this->resetPage(); }
    public function updatedStatus(): void { $this->resetPage(); }
    public function updatedRanking(): void { $this->resetPage(); }

    public function render()
    {
        $query = JobApplication::with(['job', 'applicant', 'recruiter']);

        if ($this->search) {
            $query->whereHas('applicant', fn($q) => $q->where('name', 'like', "%{$this->search}%"))
                ->orWhereHas('job', fn($q) => $q->where('job_title', 'like', "%{$this->search}%"));
        }
        if ($this->status) {
            $query->byStatus($this->status);
        }
        if ($this->ranking) {
            $query->byRanking($this->ranking);
        }

        return view('livewire.admin.applications.application-index', [
            'applications' => $query->latest('applied_at')->paginate(20),
            'statuses' => ApplicationStatusEnum::cases(),
            'rankings' => ApplicationRankingEnum::cases(),
            'stats' => [
                'total' => JobApplication::count(),
                'today' => JobApplication::whereDate('applied_at', today())->count(),
                'shortlisted' => JobApplication::byStatus('shortlisted')->count(),
                'offered' => JobApplication::byStatus('offered')->count(),
                'rejected' => JobApplication::byStatus('rejected')->count(),
                'binned' => JobApplication::onlyTrashed()->count(),
            ],
        ]);
    }
}
