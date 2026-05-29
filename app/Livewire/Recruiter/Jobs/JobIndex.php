<?php

namespace App\Livewire\Recruiter\Jobs;

use App\Enums\EmploymentTypeEnum;
use App\Models\JobBin;
use App\Models\JobPosting;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.recruiter', ['pageTitle' => 'My Job Postings'])]
class JobIndex extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';
    #[Url]
    public string $status = '';
    #[Url]
    public string $employmentType = '';

    public function updatedSearch(): void { $this->resetPage(); }
    public function updatedStatus(): void { $this->resetPage(); }
    public function updatedEmploymentType(): void { $this->resetPage(); }

    #[Computed]
    public function stats(): array
    {
        $base = JobPosting::forRecruiter(Auth::id());
        return [
            'total' => (clone $base)->count(),
            'live' => (clone $base)->approved()->active()->notExpired()->count(),
            'pending' => (clone $base)->pending()->count(),
            'rejected' => (clone $base)->rejected()->count(),
        ];
    }

    public function toggleActive(string $jobId): void
    {
        $job = JobPosting::forRecruiter(Auth::id())->findOrFail($jobId);
        $job->update(['is_active' => !$job->is_active]);
    }

    public function deleteJob(string $jobId): void
    {
        $job = JobPosting::forRecruiter(Auth::id())->findOrFail($jobId);

        JobBin::create([
            'job_id' => $job->id,
            'deleted_by_type' => 'recruiter',
            'deleted_by_id' => Auth::id(),
            'original_data' => $job->toArray(),
        ]);

        $job->delete();
        session()->flash('success', 'Job moved to bin.');
    }

    public function render()
    {
        $query = JobPosting::forRecruiter(Auth::id());

        if ($this->search) {
            $query->where('job_title', 'like', "%{$this->search}%");
        }

        if ($this->status) {
            match ($this->status) {
                'live' => $query->approved()->active()->notExpired(),
                'pending' => $query->pending(),
                'rejected' => $query->rejected(),
                'disabled' => $query->where('is_active', false),
                'expired' => $query->expired(),
                default => null,
            };
        }

        if ($this->employmentType) {
            $query->where('employment_type', $this->employmentType);
        }

        return view('livewire.recruiter.jobs.job-index', [
            'jobs' => $query->latest()->paginate(15),
            'employmentTypes' => EmploymentTypeEnum::cases(),
        ]);
    }
}
