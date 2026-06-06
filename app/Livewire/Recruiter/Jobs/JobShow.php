<?php

namespace App\Livewire\Recruiter\Jobs;

use App\Models\JobBin;
use App\Models\JobPosting;
use App\Models\JobSeekerProfile;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.recruiter', ['pageTitle' => 'Job Details'])]
class JobShow extends Component
{
    public JobPosting $job;

    public function mount(JobPosting $job): void
    {
        if ($job->recruiter_id !== Auth::id()) {
            abort(403);
        }
        $this->job = $job;
    }

    public function toggleActive(): void
    {
        $this->job->update(['is_active' => !$this->job->is_active]);
        $this->job->refresh();
    }

    public function deleteJob(): void
    {
        JobBin::create([
            'job_id' => $this->job->id,
            'deleted_by_type' => 'recruiter',
            'deleted_by_id' => Auth::id(),
            'original_data' => $this->job->toArray(),
        ]);
        $this->job->delete();
        session()->flash('success', 'Job moved to bin.');
        $this->redirect(route('recruiter.jobs.index'), navigate: true);
    }

    private function getMatchedCandidates()
    {
        $job = $this->job;
        $query = JobSeekerProfile::query()
            ->where('is_open_to_work', true)
            ->whereNotNull('first_name');

        $conditions = [];

        // Match by category
        if ($job->category_slug) {
            $conditions[] = fn($q) => $q->where('category_slug', $job->category_slug);
        }

        // Match by subcategory
        if ($job->subcategory_name) {
            $conditions[] = fn($q) => $q->where('subcategory_name', $job->subcategory_name);
        }

        // Match by specialty
        if ($job->specialty_id) {
            $conditions[] = fn($q) => $q->where('specialty_id', $job->specialty_id);
        }

        // Match by skills (JSON array overlap)
        if (!empty($job->key_skills)) {
            $conditions[] = function ($q) use ($job) {
                $q->where(function ($sq) use ($job) {
                    foreach ($job->key_skills as $skill) {
                        $sq->orWhereJsonContains('key_skills', $skill);
                    }
                });
            };
        }

        // Match by location (state)
        if ($job->location_state) {
            $conditions[] = fn($q) => $q->where('state', $job->location_state);
        }

        if (empty($conditions)) {
            return collect();
        }

        // Use OR between conditions to get broad matches
        $query->where(function ($q) use ($conditions) {
            foreach ($conditions as $i => $condition) {
                if ($i === 0) {
                    $condition($q);
                } else {
                    $q->orWhere(function ($sq) use ($condition) {
                        $condition($sq);
                    });
                }
            }
        });

        return $query->limit(20)->get();
    }

    public function render()
    {
        return view('livewire.recruiter.jobs.job-show', [
            'matchedCandidates' => $this->getMatchedCandidates(),
        ]);
    }
}
