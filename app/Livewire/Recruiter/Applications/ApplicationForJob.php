<?php

namespace App\Livewire\Recruiter\Applications;

use App\Enums\ApplicationRankingEnum;
use App\Enums\ApplicationStatusEnum;
use App\Models\JobApplication;
use App\Models\JobPosting;
use App\Services\Application\ApplicationService;
use App\Services\Application\StatusTransitionService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.recruiter', ['pageTitle' => 'Job Applicants'])]
class ApplicationForJob extends Component
{
    use WithPagination;

    public JobPosting $job;

    #[Url] public string $search = '';
    #[Url] public string $status = '';
    #[Url] public string $ranking = '';

    public function mount(JobPosting $job): void
    {
        if ($job->recruiter_id !== auth()->id()) {
            abort(403);
        }
        $this->job = $job;
    }

    public function updatedSearch(): void { $this->resetPage(); }
    public function updatedStatus(): void { $this->resetPage(); }
    public function updatedRanking(): void { $this->resetPage(); }

    public function updateStatus(string $applicationId, string $newStatus): void
    {
        $application = JobApplication::findOrFail($applicationId);

        if (!$application->isOwnedByRecruiter(auth()->id())) {
            abort(403);
        }

        try {
            app(ApplicationService::class)->updateStatus($application, $newStatus);
            session()->flash('message', "Status updated to {$newStatus}.");
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function render()
    {
        $query = JobApplication::forJob($this->job->id)->with(['applicant.jobSeekerProfile']);

        if ($this->search) {
            $query->whereHas('applicant', fn($q) => $q->where('name', 'like', "%{$this->search}%"));
        }
        if ($this->status) {
            $query->byStatus($this->status);
        }
        if ($this->ranking) {
            $query->byRanking($this->ranking);
        }

        $baseQuery = JobApplication::forJob($this->job->id);

        return view('livewire.recruiter.applications.application-for-job', [
            'applications' => $query->orderedByRanking()->latest('applied_at')->paginate(20),
            'statuses' => ApplicationStatusEnum::cases(),
            'rankings' => ApplicationRankingEnum::cases(),
            'transitionService' => app(StatusTransitionService::class),
            'stats' => [
                'total' => (clone $baseQuery)->count(),
                'applied' => (clone $baseQuery)->byStatus('applied')->count(),
                'reviewed' => (clone $baseQuery)->byStatus('reviewed')->count(),
                'shortlisted' => (clone $baseQuery)->byStatus('shortlisted')->count(),
                'interviewed' => (clone $baseQuery)->byStatus('interviewed')->count(),
                'offered' => (clone $baseQuery)->byStatus('offered')->count(),
                'rejected' => (clone $baseQuery)->byStatus('rejected')->count(),
            ],
        ]);
    }
}
