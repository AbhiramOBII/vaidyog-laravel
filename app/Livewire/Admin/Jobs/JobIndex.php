<?php

namespace App\Livewire\Admin\Jobs;

use App\Enums\EmploymentTypeEnum;
use App\Models\AdminActionLog;
use App\Models\JobBin;
use App\Models\JobPosting;
use App\Models\Specialty;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.admin', ['pageTitle' => 'Job Postings'])]
class JobIndex extends Component
{
    use WithPagination;

    #[Url] public string $search = '';
    #[Url] public string $approvalStatus = '';
    #[Url] public string $activeStatus = '';
    #[Url] public string $featured = '';
    #[Url] public string $employmentType = '';
    #[Url] public string $categorySlug = '';
    #[Url] public string $specialtyId = '';
    #[Url] public bool $expiringSoon = false;

    public function updatedSearch(): void { $this->resetPage(); }

    #[Computed]
    public function stats(): array
    {
        return [
            'total' => JobPosting::count(),
            'live' => JobPosting::publiclyVisible()->count(),
            'pending' => JobPosting::pending()->count(),
            'featured' => JobPosting::featured()->count(),
            'expired' => JobPosting::expired()->count(),
        ];
    }

    public function approve(string $jobId): void
    {
        $job = JobPosting::findOrFail($jobId);
        $job->update([
            'admin_approved' => true,
            'approved_at' => now(),
            'approved_by_admin_id' => Auth::guard('admin')->id(),
            'rejection_reason' => null,
            'rejected_at' => null,
            'rejected_by_admin_id' => null,
            'expires_at' => now()->addDays($job->posting_duration_days),
        ]);
        AdminActionLog::create(['admin_id' => Auth::guard('admin')->id(), 'action' => 'job_approved', 'target_type' => 'job_posting', 'target_id' => $job->id, 'details' => []]);
    }

    public function toggleFeatured(string $jobId): void
    {
        $job = JobPosting::findOrFail($jobId);
        $job->update([
            'is_featured' => !$job->is_featured,
            'featured_at' => !$job->is_featured ? now() : null,
        ]);
        AdminActionLog::create(['admin_id' => Auth::guard('admin')->id(), 'action' => $job->is_featured ? 'job_featured' : 'job_unfeatured', 'target_type' => 'job_posting', 'target_id' => $job->id, 'details' => []]);
    }

    public function toggleActive(string $jobId): void
    {
        $job = JobPosting::findOrFail($jobId);
        $job->update(['is_active' => !$job->is_active]);
    }

    public function moveToBin(string $jobId): void
    {
        $job = JobPosting::findOrFail($jobId);
        JobBin::create(['job_id' => $job->id, 'deleted_by_type' => 'admin', 'deleted_by_id' => Auth::guard('admin')->id(), 'original_data' => $job->toArray()]);
        $job->delete();
        AdminActionLog::create(['admin_id' => Auth::guard('admin')->id(), 'action' => 'job_deleted', 'target_type' => 'job_posting', 'target_id' => $job->id, 'details' => []]);
    }

    public function render()
    {
        $query = JobPosting::with(['recruiter', 'specialty']);

        if ($this->search) {
            $query->where(fn($q) => $q->where('job_title', 'like', "%{$this->search}%")->orWhere('institution_name', 'like', "%{$this->search}%"));
        }
        if ($this->approvalStatus) {
            match ($this->approvalStatus) {
                'approved' => $query->approved(),
                'pending' => $query->pending(),
                'rejected' => $query->rejected(),
                default => null,
            };
        }
        if ($this->activeStatus) {
            match ($this->activeStatus) {
                'active' => $query->where('is_active', true),
                'disabled' => $query->where('is_active', false),
                default => null,
            };
        }
        if ($this->featured) {
            match ($this->featured) {
                'yes' => $query->featured(),
                'no' => $query->where('is_featured', false),
                default => null,
            };
        }
        if ($this->employmentType) $query->where('employment_type', $this->employmentType);
        if ($this->categorySlug) $query->where('category_slug', $this->categorySlug);
        if ($this->specialtyId) $query->where('specialty_id', $this->specialtyId);
        if ($this->expiringSoon) $query->expiringSoon();

        return view('livewire.admin.jobs.job-index', [
            'jobs' => $query->latest()->paginate(20),
            'employmentTypes' => EmploymentTypeEnum::cases(),
            'specialties' => Specialty::active()->ordered()->get(),
        ]);
    }
}
