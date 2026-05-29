<?php

namespace App\Livewire\Frontend\Jobs;

use App\Enums\EmploymentTypeEnum;
use App\Models\JobPosting;
use App\Models\Specialty;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.public')]
class JobIndex extends Component
{
    use WithPagination;

    #[Url] public string $search = '';
    #[Url] public string $city = '';
    #[Url] public string $type = '';
    #[Url] public string $category = '';
    #[Url] public array $specialty = [];
    #[Url] public string $exp = '';
    #[Url] public string $sort = 'latest';

    public function updatedSearch(): void { $this->resetPage(); }
    public function updatedCity(): void { $this->resetPage(); }
    public function updatedType(): void { $this->resetPage(); }
    public function updatedCategory(): void { $this->resetPage(); }
    public function toggleSpecialty(int $id): void
    {
        if (in_array($id, $this->specialty)) {
            $this->specialty = array_values(array_diff($this->specialty, [$id]));
        } else {
            $this->specialty[] = $id;
        }
        $this->resetPage();
    }
    public function updatedExp(): void { $this->resetPage(); }
    public function setCity(string $value): void { $this->city = $value; $this->resetPage(); }

    public function clearFilter(string $filter): void
    {
        $this->$filter = $filter === 'specialty' ? [] : '';
        $this->resetPage();
    }

    public function clearAll(): void
    {
        $this->search = '';
        $this->city = '';
        $this->type = '';
        $this->category = '';
        $this->specialty = [];
        $this->exp = '';
        $this->resetPage();
    }

    public function render()
    {
        $query = JobPosting::publiclyVisible()->with(['recruiter.profile', 'specialty']);

        if ($this->search) {
            $term = $this->search;
            $query->where(fn($q) => $q->where('job_title', 'like', "%{$term}%")
                ->orWhere('institution_name', 'like', "%{$term}%")
                ->orWhereJsonContains('key_skills', $term));
        }
        if ($this->city) {
            $query->where('location_city', 'like', "%{$this->city}%");
        }
        if ($this->type) {
            $query->where('employment_type', $this->type);
        }
        if ($this->category) {
            $query->where('category_slug', $this->category);
        }
        if (!empty($this->specialty)) {
            $query->whereIn('specialty_id', $this->specialty);
        }
        if ($this->exp) {
            match ($this->exp) {
                '0-1' => $query->where('experience_min', '<=', 1),
                '1-3' => $query->whereBetween('experience_min', [1, 3]),
                '3-5' => $query->whereBetween('experience_min', [3, 5]),
                '5-10' => $query->whereBetween('experience_min', [5, 10]),
                '10+' => $query->where('experience_min', '>=', 10),
                default => null,
            };
        }

        match ($this->sort) {
            'salary' => $query->orderByDesc('salary_max'),
            'experience' => $query->orderBy('experience_min'),
            default => $query->latest('approved_at'),
        };

        $locations = JobPosting::publiclyVisible()
            ->whereNotNull('location_city')
            ->where('location_city', '!=', '')
            ->selectRaw('location_city, location_state, COUNT(*) as jobs_count')
            ->groupBy('location_city', 'location_state')
            ->orderByDesc('jobs_count')
            ->limit(20)
            ->get();

        return view('livewire.frontend.jobs.job-index', [
            'jobs' => $query->paginate(12),
            'employmentTypes' => EmploymentTypeEnum::cases(),
            'specialties' => Specialty::active()->ordered()
                ->whereHas('jobs', fn ($q) => $q->publiclyVisible())
                ->get(),
            'totalCount' => $query->count(),
            'locations' => $locations,
        ]);
    }
}
