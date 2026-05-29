<?php

namespace App\Livewire\Admin\JobSeekers;

use App\Enums\AuthProviderEnum;
use App\Enums\UserStatusEnum;
use App\Models\JobCategory;
use App\Models\JobSubcategory;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.admin')]
class JobSeekerIndex extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    #[Url]
    public string $status = '';

    #[Url]
    public string $category = '';

    #[Url]
    public string $subcategory = '';

    #[Url(as: 'provider')]
    public string $authProvider = '';

    #[Url(as: 'profile')]
    public string $profileCompleted = '';

    #[Url(as: 'from')]
    public string $dateFrom = '';

    #[Url(as: 'to')]
    public string $dateTo = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatus(): void
    {
        $this->resetPage();
    }

    public function updatingCategory(): void
    {
        $this->resetPage();
    }

    public function updatingSubcategory(): void
    {
        $this->resetPage();
    }

    public function updatingAuthProvider(): void
    {
        $this->resetPage();
    }

    public function updatingProfileCompleted(): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->reset(['search', 'status', 'category', 'subcategory', 'authProvider', 'profileCompleted', 'dateFrom', 'dateTo']);
        $this->resetPage();
    }

    public function toggleStatus(string $userId, string $newStatus): void
    {
        $user = User::jobSeekers()->findOrFail($userId);
        $user->update(['status' => $newStatus]);
    }

    public function exportCsv(): void
    {
        // Stub action
        session()->flash('message', 'CSV export feature coming soon.');
    }

    public function getStatsProperty(): array
    {
        $base = User::jobSeekers();

        return [
            'total' => (clone $base)->count(),
            'active' => (clone $base)->where('status', UserStatusEnum::Active)->count(),
            'pending' => (clone $base)->where('status', UserStatusEnum::PendingVerification)->count(),
            'blocked' => (clone $base)->where('status', UserStatusEnum::Blocked)->count(),
        ];
    }

    public function getCategoriesProperty()
    {
        return JobCategory::where('is_active', true)->orderBy('sort_order')->get(['id', 'slug', 'name']);
    }

    public function getSubcategoriesProperty()
    {
        $query = JobSubcategory::where('is_active', true)->orderBy('sort_order');

        if ($this->category) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $this->category));
        }

        return $query->get(['id', 'job_category_id', 'name', 'slug']);
    }

    public function render()
    {
        $query = User::jobSeekers()
            ->with('jobSeekerProfile')
            ->orderBy('created_at', 'desc');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('email', 'like', "%{$this->search}%")
                  ->orWhere('phone', 'like', "%{$this->search}%");
            });
        }

        if ($this->status) {
            $query->where('status', $this->status);
        }

        if ($this->authProvider) {
            $query->where('auth_provider', $this->authProvider);
        }

        if ($this->profileCompleted !== '') {
            if ($this->profileCompleted === 'yes') {
                $query->where('is_profile_completed', true);
            } elseif ($this->profileCompleted === 'no') {
                $query->where('is_profile_completed', false);
            }
        }

        if ($this->category) {
            $query->whereHas('jobSeekerProfile', fn ($q) => $q->where('category_slug', $this->category));
        }

        if ($this->subcategory) {
            $query->whereHas('jobSeekerProfile', fn ($q) => $q->where('subcategory_name', $this->subcategory));
        }

        if ($this->dateFrom) {
            $query->whereDate('created_at', '>=', $this->dateFrom);
        }

        if ($this->dateTo) {
            $query->whereDate('created_at', '<=', $this->dateTo);
        }

        return view('livewire.admin.job-seekers.job-seeker-index', [
            'jobSeekers' => $query->paginate(15),
            'statuses' => UserStatusEnum::cases(),
            'authProviders' => AuthProviderEnum::cases(),
        ]);
    }
}
