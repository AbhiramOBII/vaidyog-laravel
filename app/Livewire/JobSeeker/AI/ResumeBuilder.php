<?php

namespace App\Livewire\JobSeeker\AI;

use App\Services\AIResumeService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('components.layouts.app', ['pageTitle' => 'AI Resume Builder'])]
class ResumeBuilder extends Component
{
    use WithFileUploads;

    public function mount(): void
    {
        if (!auth()->user()->activeSubscription()) {
            session()->flash('warning', 'AI Resume Builder is available for paid subscribers only. Please upgrade your plan to access this feature.');
            $this->redirect(route('plans.index'), navigate: true);
        }
    }

    // Step tracking
    public int $step = 1;

    // Step 1 inputs
    public string $inputMethod = 'file'; // 'file' or 'manual'
    public $resumeFile = null;
    public string $manualContent = '';
    public string $tone = 'professional';
    public bool $includeImage = true;

    // Step 2 state
    public bool $processing = false;
    public string $processingStatus = '';

    // Step 3 output
    public array $resumeData = [];
    public bool $showUpdatePrompt = false;
    public bool $resumeSaved = false;
    public string $error = '';

    public function updatedResumeFile(): void
    {
        $this->validateOnly('resumeFile', [
            'resumeFile' => 'file|mimes:pdf,doc,docx,txt|max:5120',
        ]);
    }

    public function startProcessing(): void
    {
        if ($this->inputMethod === 'file') {
            $this->validate([
                'resumeFile' => 'required|file|mimes:pdf,doc,docx,txt|max:5120',
            ]);
        } else {
            $this->validate([
                'manualContent' => 'required|min:50',
            ], [
                'manualContent.required' => 'Please provide your resume content.',
                'manualContent.min' => 'Please provide at least 50 characters of content.',
            ]);
        }

        $this->step = 2;
        $this->processing = true;
        $this->error = '';

        $this->dispatch('start-ai-processing');
    }

    public function processResume(): void
    {
        try {
            $this->processingStatus = 'Extracting content...';

            $content = '';
            if ($this->inputMethod === 'file' && $this->resumeFile) {
                $path = $this->resumeFile->getRealPath();
                $extension = $this->resumeFile->getClientOriginalExtension();

                $service = app(AIResumeService::class);

                if ($extension === 'pdf') {
                    $content = $service->extractTextFromPdf($path);
                } elseif (in_array($extension, ['doc', 'docx'])) {
                    $content = $service->extractTextFromDocx($path);
                } else {
                    $content = $service->sanitizeUtf8(file_get_contents($path));
                }
            } else {
                $content = $this->manualContent;
            }

            $this->processingStatus = 'AI is crafting your resume...';

            // Get profile data to enhance resume
            $user = auth()->user();
            $profile = $user->jobSeekerProfile;
            $profileData = [];

            if ($profile) {
                $profileData = [
                    'name' => $profile->getFullName(),
                    'email' => $profile->email ?? $user->email,
                    'phone' => $profile->phone ?? $user->phone,
                    'location' => collect([$profile->city, $profile->state])->filter()->join(', '),
                    'designation' => $profile->designation,
                    'experience_years' => $profile->experience_years,
                    'skills' => $profile->key_skills ?? [],
                    'qualification' => $profile->highest_qualification,
                    'current_employer' => $profile->current_employer,
                ];
            }

            $this->resumeData = app(AIResumeService::class)->buildResume([
                'source' => $this->inputMethod,
                'content' => $content,
                'tone' => $this->tone,
                'include_image' => $this->includeImage,
                'profile_data' => $profileData,
            ]);

            $this->processingStatus = 'Done!';
            $this->processing = false;
            $this->step = 3;

        } catch (\Exception $e) {
            $this->error = 'Something went wrong: ' . $e->getMessage();
            $this->processing = false;
            $this->step = 1;
        }
    }

    public function downloadPdf()
    {
        $user = auth()->user();
        $profile = $user->jobSeekerProfile;
        $imageUrl = null;

        if ($this->includeImage && $profile?->profile_photo_path) {
            $imageUrl = storage_path('app/public/' . $profile->profile_photo_path);
            if (!file_exists($imageUrl)) {
                $imageUrl = null;
            }
        }

        $pdf = Pdf::loadView('pdf.resume', [
            'resume' => $this->resumeData,
            'includeImage' => $this->includeImage,
            'imageUrl' => $imageUrl,
        ]);

        $filename = 'resume-' . ($this->resumeData['name'] ?? 'download') . '.pdf';
        $filename = preg_replace('/[^a-zA-Z0-9\-\.]/', '-', $filename);

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, $filename, ['Content-Type' => 'application/pdf']);
    }

    public function updateProfile(): void
    {
        $user = auth()->user();
        $profile = $user->jobSeekerProfile;

        if (!$profile) return;

        $updates = [];

        if (!empty($this->resumeData['skills'])) {
            $updates['key_skills'] = array_slice($this->resumeData['skills'], 0, 30);
        }
        if (!empty($this->resumeData['title']) && empty($profile->designation)) {
            $updates['designation'] = $this->resumeData['title'];
        }
        if (!empty($this->resumeData['summary']) && empty($profile->about)) {
            $updates['about'] = $this->resumeData['summary'];
        }

        if (!empty($updates)) {
            $profile->update($updates);
        }

        $this->showUpdatePrompt = false;
        $this->dispatch('profile-updated');
    }

    public function saveResume(): void
    {
        $user = auth()->user();
        $profile = $user->jobSeekerProfile;

        if (!$profile) return;

        $imageUrl = null;
        if ($this->includeImage && $profile->profile_photo_path) {
            $imageUrl = storage_path('app/public/' . $profile->profile_photo_path);
            if (!file_exists($imageUrl)) {
                $imageUrl = null;
            }
        }

        $pdf = Pdf::loadView('pdf.resume', [
            'resume' => $this->resumeData,
            'includeImage' => $this->includeImage,
            'imageUrl' => $imageUrl,
        ]);

        $filename = 'resumes/' . $user->id . '/ai-resume-' . time() . '.pdf';
        Storage::disk('public')->put($filename, $pdf->output());

        // Remove old AI resume if exists
        if ($profile->resume_path && Storage::disk('public')->exists($profile->resume_path)) {
            Storage::disk('public')->delete($profile->resume_path);
        }

        $profile->update(['resume_path' => $filename]);
        $this->resumeSaved = true;
    }

    public function startOver(): void
    {
        $this->reset(['step', 'resumeFile', 'manualContent', 'resumeData', 'error', 'processing', 'processingStatus', 'showUpdatePrompt', 'resumeSaved']);
        $this->step = 1;
    }

    public function render()
    {
        return view('livewire.job-seeker.ai.resume-builder');
    }
}
