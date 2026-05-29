<?php

namespace App\Livewire\Frontend\Jobs;

use App\Models\JobPosting;
use App\Models\SavedJob;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;

#[Layout('layouts.public')]
class JobDetail extends Component
{
    public JobPosting $job;
    public bool $showApplyModal = false;

    public function mount(JobPosting $job): void
    {
        if (!$job->admin_approved || !$job->is_active || $job->trashed()) {
            abort(404);
        }
        $this->job = $job->load('recruiter.profile');
    }

    #[Computed]
    public function hasApplied(): bool
    {
        if (!auth()->check()) return false;
        return $this->job->applications()
            ->where('applicant_id', auth()->id())
            ->whereNull('deleted_at')
            ->exists();
    }

    #[Computed]
    public function applicationStatus(): ?string
    {
        if (!auth()->check()) return null;
        $app = $this->job->applications()
            ->where('applicant_id', auth()->id())
            ->whereNull('deleted_at')
            ->first();
        return $app?->status?->value;
    }

    #[Computed]
    public function isSaved(): bool
    {
        if (!auth()->check()) return false;
        return SavedJob::where('job_id', $this->job->id)->where('user_id', auth()->id())->exists();
    }

    #[Computed]
    public function isExpired(): bool
    {
        return $this->job->expires_at && $this->job->expires_at->isPast();
    }

    #[Computed]
    public function isOwnJob(): bool
    {
        if (!auth()->check()) return false;
        return $this->job->recruiter_id === auth()->id();
    }

    public function toggleSave(): void
    {
        if (!auth()->check()) {
            $this->redirect(route('jobseeker.login', ['return_url' => url()->current()]), navigate: true);
            return;
        }

        $existing = SavedJob::where('job_id', $this->job->id)->where('user_id', auth()->id())->first();
        if ($existing) {
            $existing->delete();
        } else {
            SavedJob::create(['job_id' => $this->job->id, 'user_id' => auth()->id()]);
        }
        unset($this->isSaved);
    }

    #[On('application-submitted')]
    public function onApplicationSubmitted(): void
    {
        $this->showApplyModal = false;
        unset($this->hasApplied);
        unset($this->applicationStatus);
    }

    public function render()
    {
        return view('livewire.frontend.jobs.job-detail');
    }
}
