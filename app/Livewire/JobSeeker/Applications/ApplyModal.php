<?php

namespace App\Livewire\JobSeeker\Applications;

use App\Exceptions\ApplyLimitExceededException;
use App\Exceptions\DuplicateApplicationException;
use App\Models\JobPosting;
use App\Services\Application\ApplicationService;
use Livewire\Component;
use Livewire\WithFileUploads;

class ApplyModal extends Component
{
    use WithFileUploads;

    public string $jobId = '';
    public string $coverNote = '';
    public $resumeFile = null;
    public bool $useProfileResume = true;
    public string $errorMessage = '';
    public bool $success = false;
    public bool $showUpgradeCta = false;

    public ?JobPosting $job = null;
    public bool $alreadyApplied = false;

    public function mount(string $jobId): void
    {
        $this->jobId = $jobId;
        $this->job = JobPosting::find($jobId);

        if (!$this->job) {
            $this->errorMessage = 'Job not found.';
            return;
        }

        $this->alreadyApplied = auth()->user()->applications()
            ->where('job_id', $jobId)
            ->whereNull('deleted_at')
            ->exists();
    }

    public function submit(): void
    {
        $this->errorMessage = '';

        $this->validate([
            'coverNote' => ['nullable', 'string', 'max:500'],
            'resumeFile' => ['nullable', 'file', 'mimes:pdf', 'max:5120'],
        ]);

        $data = [
            'cover_note' => $this->coverNote ?: null,
        ];

        if ($this->resumeFile) {
            $data['resume_path'] = $this->resumeFile->store('application-resumes', 'public');
        } elseif (!$this->useProfileResume) {
            $data['resume_path'] = null;
        }

        try {
            app(ApplicationService::class)->applyToJob(auth()->user(), $this->job, $data);
            $this->success = true;
            $this->dispatch('application-submitted');
        } catch (ApplyLimitExceededException $e) {
            $this->errorMessage = $e->getMessage();
            $this->showUpgradeCta = true;
        } catch (DuplicateApplicationException $e) {
            $this->errorMessage = $e->getMessage();
        } catch (\Exception $e) {
            $this->errorMessage = $e->getMessage();
        }
    }

    public function render()
    {
        return view('livewire.job-seeker.applications.apply-modal');
    }
}
