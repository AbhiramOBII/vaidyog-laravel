<?php

namespace App\Livewire\Admin\Jobs;

use App\Models\AdminActionLog;
use App\Models\JobBin;
use App\Models\JobPosting;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.admin', ['pageTitle' => 'Job Details'])]
class JobShow extends Component
{
    public JobPosting $job;
    public string $rejectionReason = '';
    public bool $showRejectForm = false;

    public function mount(JobPosting $job): void
    {
        $this->job = $job->load('recruiter');
    }

    public function approve(): void
    {
        $this->job->update([
            'admin_approved' => true,
            'approved_at' => now(),
            'approved_by_admin_id' => Auth::guard('admin')->id(),
            'rejection_reason' => null,
            'rejected_at' => null,
            'rejected_by_admin_id' => null,
            'expires_at' => now()->addDays($this->job->posting_duration_days),
        ]);
        AdminActionLog::create(['admin_id' => Auth::guard('admin')->id(), 'action' => 'job_approved', 'target_type' => 'job_posting', 'target_id' => $this->job->id, 'details' => []]);
        $this->job->refresh();
        session()->flash('success', 'Job approved and is now live.');
    }

    public function confirmReject(): void
    {
        $this->validate(['rejectionReason' => 'required|min:10']);
        $this->job->update([
            'rejection_reason' => $this->rejectionReason,
            'rejected_at' => now(),
            'rejected_by_admin_id' => Auth::guard('admin')->id(),
            'admin_approved' => false,
        ]);
        AdminActionLog::create(['admin_id' => Auth::guard('admin')->id(), 'action' => 'job_rejected', 'target_type' => 'job_posting', 'target_id' => $this->job->id, 'details' => ['reason' => $this->rejectionReason]]);
        $this->job->refresh();
        $this->showRejectForm = false;
        session()->flash('success', 'Job rejected.');
    }

    public function toggleFeatured(): void
    {
        $this->job->update([
            'is_featured' => !$this->job->is_featured,
            'featured_at' => !$this->job->is_featured ? now() : null,
        ]);
        $this->job->refresh();
    }

    public function toggleActive(): void
    {
        $this->job->update(['is_active' => !$this->job->is_active]);
        $this->job->refresh();
    }

    public function moveToBin(): void
    {
        JobBin::create(['job_id' => $this->job->id, 'deleted_by_type' => 'admin', 'deleted_by_id' => Auth::guard('admin')->id(), 'original_data' => $this->job->toArray()]);
        $this->job->delete();
        AdminActionLog::create(['admin_id' => Auth::guard('admin')->id(), 'action' => 'job_deleted', 'target_type' => 'job_posting', 'target_id' => $this->job->id, 'details' => []]);
        session()->flash('success', 'Job moved to bin.');
        $this->redirect(route('admin.jobs.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.jobs.job-show');
    }
}
