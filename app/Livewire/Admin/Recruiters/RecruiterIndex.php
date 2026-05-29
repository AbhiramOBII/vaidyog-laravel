<?php

namespace App\Livewire\Admin\Recruiters;

use App\Enums\AuthProviderEnum;
use App\Enums\MedTypeEnum;
use App\Enums\UserStatusEnum;
use App\Models\AdminActionLog;
use App\Models\MedicalInstitution;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.admin')]
class RecruiterIndex extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    #[Url]
    public string $status = '';

    #[Url(as: 'type')]
    public string $medType = '';

    #[Url]
    public string $featured = '';

    #[Url(as: 'provider')]
    public string $authProvider = '';

    #[Url(as: 'profile')]
    public string $profileCompleted = '';

    #[Url(as: 'from')]
    public string $dateFrom = '';

    #[Url(as: 'to')]
    public string $dateTo = '';

    #[Url]
    public string $sortBy = 'created_at';

    #[Url]
    public string $sortDir = 'desc';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatedStatus(): void
    {
        $this->resetPage();
    }

    public function updatedMedType(): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->reset(['search', 'status', 'medType', 'featured', 'authProvider', 'profileCompleted', 'dateFrom', 'dateTo']);
        $this->resetPage();
    }

    public function sortByColumn(string $column): void
    {
        if ($this->sortBy === $column) {
            $this->sortDir = $this->sortDir === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDir = 'asc';
        }
    }

    public function toggleFeatured(string $userId): void
    {
        $recruiter = MedicalInstitution::findOrFail($userId);
        $profile = $recruiter->profile;

        if (!$profile) return;

        $newState = !$profile->is_featured;
        $profile->update([
            'is_featured' => $newState,
            'featured_at' => $newState ? now() : null,
        ]);

        AdminActionLog::create([
            'admin_id' => Auth::guard('admin')->id(),
            'target_type' => 'MedicalInstitution',
            'target_id' => $userId,
            'action' => $newState ? 'featured' : 'unfeatured',
            'notes' => $newState ? 'Marked as featured.' : 'Featured status removed.',
        ]);

        session()->flash('message', $newState ? 'Recruiter marked as featured.' : 'Featured status removed.');
    }

    public function changeStatus(string $userId, string $newStatus): void
    {
        $recruiter = MedicalInstitution::findOrFail($userId);
        $recruiter->update(['status' => $newStatus]);

        AdminActionLog::create([
            'admin_id' => Auth::guard('admin')->id(),
            'target_type' => 'MedicalInstitution',
            'target_id' => $userId,
            'action' => 'status_change',
            'notes' => "Changed to {$newStatus}",
        ]);

        session()->flash('message', 'Recruiter status updated.');
    }

    public function exportCsv()
    {
        $query = $this->buildQuery();

        return response()->streamDownload(function () use ($query) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'Institution Name', 'Med Type', 'Contact Person Name', 'Email', 'Phone',
                'Status', 'Featured', 'Referral Code', 'City', 'State',
                'Auth Provider', 'Profile Completed', 'Registered On',
            ]);

            $query->with('profile')->chunk(200, function ($recruiters) use ($handle) {
                foreach ($recruiters as $r) {
                    fputcsv($handle, [
                        $r->profile?->institution_name ?? '',
                        $r->profile?->med_type?->label() ?? '',
                        $r->profile?->contact_person_name ?? '',
                        $r->email ?? '',
                        $r->phone ?? '',
                        $r->status?->label() ?? '',
                        $r->profile?->is_featured ? 'yes' : 'no',
                        $r->profile?->referral_code ?? '',
                        $r->profile?->city ?? '',
                        $r->profile?->state ?? '',
                        $r->auth_provider?->label() ?? '',
                        $r->profile?->is_profile_completed ? 'yes' : 'no',
                        $r->created_at?->format('Y-m-d'),
                    ]);
                }
            });

            fclose($handle);
        }, 'recruiters_export_' . date('Y-m-d') . '.csv');
    }

    #[Computed]
    public function stats(): array
    {
        $base = MedicalInstitution::query();
        return [
            'total' => (clone $base)->count(),
            'active' => (clone $base)->where('status', UserStatusEnum::Active)->count(),
            'pending' => (clone $base)->where('status', UserStatusEnum::PendingVerification)->count(),
            'featured' => (clone $base)->featured()->count(),
            'blocked' => (clone $base)->where('status', UserStatusEnum::Blocked)->count(),
        ];
    }

    protected function buildQuery()
    {
        $query = MedicalInstitution::with('profile');

        if ($this->search) {
            $search = $this->search;
            $query->where(function ($q) use ($search) {
                $q->where('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhereHas('profile', function ($pq) use ($search) {
                      $pq->where('institution_name', 'like', "%{$search}%")
                         ->orWhere('contact_person_name', 'like', "%{$search}%");
                  });
            });
        }

        if ($this->status) {
            $query->where('status', $this->status);
        }

        if ($this->medType) {
            $query->whereHas('profile', fn ($q) => $q->where('med_type', $this->medType));
        }

        if ($this->featured === 'yes') {
            $query->whereHas('profile', fn ($q) => $q->where('is_featured', true));
        } elseif ($this->featured === 'no') {
            $query->whereHas('profile', fn ($q) => $q->where('is_featured', false));
        }

        if ($this->authProvider) {
            $query->where('auth_provider', $this->authProvider);
        }

        if ($this->profileCompleted === 'yes') {
            $query->whereHas('profile', fn ($q) => $q->where('is_profile_completed', true));
        } elseif ($this->profileCompleted === 'no') {
            $query->whereHas('profile', fn ($q) => $q->where('is_profile_completed', false));
        }

        if ($this->dateFrom) {
            $query->whereDate('created_at', '>=', $this->dateFrom);
        }

        if ($this->dateTo) {
            $query->whereDate('created_at', '<=', $this->dateTo);
        }

        $sortColumn = match ($this->sortBy) {
            'institution_name' => null,
            'status' => 'status',
            default => 'created_at',
        };

        if ($sortColumn) {
            $query->orderBy($sortColumn, $this->sortDir);
        } else {
            $query->orderBy('created_at', $this->sortDir);
        }

        return $query;
    }

    public function render()
    {
        return view('livewire.admin.recruiters.recruiter-index', [
            'recruiters' => $this->buildQuery()->paginate(15),
            'statuses' => UserStatusEnum::cases(),
            'medTypes' => MedTypeEnum::cases(),
            'authProviders' => AuthProviderEnum::cases(),
        ]);
    }
}
