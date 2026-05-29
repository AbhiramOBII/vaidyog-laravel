<?php

namespace App\Livewire\Admin\Jobs;

use App\Models\AdminActionLog;
use App\Models\JobPosting;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.admin', ['pageTitle' => 'Pending Job Approvals'])]
class JobPending extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';
    #[Url]
    public string $categorySlug = '';

    public array $selected = [];
    public string $rejectingJobId = '';
    public string $rejectionReason = '';
    public string $bulkRejectionReason = '';
    public bool $showBulkRejectModal = false;

    public function updatedSearch(): void { $this->resetPage(); }

    public function approve(string $jobId): void
    {
        $job = JobPosting::pending()->findOrFail($jobId);
        $job->update([
            'admin_approved' => true,
            'approved_at' => now(),
            'approved_by_admin_id' => Auth::guard('admin')->id(),
            'expires_at' => now()->addDays($job->posting_duration_days),
        ]);

        AdminActionLog::create([
            'admin_id' => Auth::guard('admin')->id(),
            'action' => 'job_approved',
            'target_type' => 'job_posting',
            'target_id' => $job->id,
            'details' => ['job_title' => $job->job_title],
        ]);

        session()->flash('success', 'Job approved and is now live.');
    }

    public function startReject(string $jobId): void
    {
        $this->rejectingJobId = $jobId;
        $this->rejectionReason = '';
    }

    public function cancelReject(): void
    {
        $this->rejectingJobId = '';
        $this->rejectionReason = '';
    }

    public function confirmReject(): void
    {
        $this->validate(['rejectionReason' => 'required|min:10']);

        $job = JobPosting::pending()->findOrFail($this->rejectingJobId);
        $job->update([
            'rejection_reason' => $this->rejectionReason,
            'rejected_at' => now(),
            'rejected_by_admin_id' => Auth::guard('admin')->id(),
        ]);

        AdminActionLog::create([
            'admin_id' => Auth::guard('admin')->id(),
            'action' => 'job_rejected',
            'target_type' => 'job_posting',
            'target_id' => $job->id,
            'details' => ['reason' => $this->rejectionReason],
        ]);

        $this->rejectingJobId = '';
        $this->rejectionReason = '';
        session()->flash('success', 'Job rejected.');
    }

    public function bulkApprove(): void
    {
        if (empty($this->selected)) return;

        $jobs = JobPosting::pending()->whereIn('id', $this->selected)->get();
        foreach ($jobs as $job) {
            $job->update([
                'admin_approved' => true,
                'approved_at' => now(),
                'approved_by_admin_id' => Auth::guard('admin')->id(),
                'expires_at' => now()->addDays($job->posting_duration_days),
            ]);
            AdminActionLog::create([
                'admin_id' => Auth::guard('admin')->id(),
                'action' => 'job_approved',
                'target_type' => 'job_posting',
                'target_id' => $job->id,
                'details' => ['bulk' => true],
            ]);
        }

        $this->selected = [];
        session()->flash('success', count($jobs) . ' jobs approved.');
    }

    public function openBulkReject(): void
    {
        if (empty($this->selected)) return;
        $this->showBulkRejectModal = true;
        $this->bulkRejectionReason = '';
    }

    public function confirmBulkReject(): void
    {
        $this->validate(['bulkRejectionReason' => 'required|min:10']);

        $jobs = JobPosting::pending()->whereIn('id', $this->selected)->get();
        foreach ($jobs as $job) {
            $job->update([
                'rejection_reason' => $this->bulkRejectionReason,
                'rejected_at' => now(),
                'rejected_by_admin_id' => Auth::guard('admin')->id(),
            ]);
            AdminActionLog::create([
                'admin_id' => Auth::guard('admin')->id(),
                'action' => 'job_rejected',
                'target_type' => 'job_posting',
                'target_id' => $job->id,
                'details' => ['reason' => $this->bulkRejectionReason, 'bulk' => true],
            ]);
        }

        $this->selected = [];
        $this->showBulkRejectModal = false;
        $this->bulkRejectionReason = '';
        session()->flash('success', count($jobs) . ' jobs rejected.');
    }

    public function render()
    {
        $query = JobPosting::pending()->with('recruiter');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('job_title', 'like', "%{$this->search}%")
                  ->orWhere('institution_name', 'like', "%{$this->search}%");
            });
        }

        if ($this->categorySlug) {
            $query->where('category_slug', $this->categorySlug);
        }

        return view('livewire.admin.jobs.job-pending', [
            'jobs' => $query->latest()->paginate(20),
            'pendingCount' => JobPosting::pending()->count(),
        ]);
    }
}
