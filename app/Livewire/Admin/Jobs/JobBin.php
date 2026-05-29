<?php

namespace App\Livewire\Admin\Jobs;

use App\Models\AdminActionLog;
use App\Models\JobBin as JobBinModel;
use App\Models\JobPosting;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.admin', ['pageTitle' => 'Job Bin'])]
class JobBin extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    public function updatedSearch(): void { $this->resetPage(); }

    public function restore(string $binId): void
    {
        $bin = JobBinModel::findOrFail($binId);
        $job = JobPosting::withTrashed()->find($bin->job_id);

        if ($job) {
            $job->restore();
            AdminActionLog::create([
                'admin_id' => Auth::guard('admin')->id(),
                'action' => 'job_restored',
                'target_type' => 'job_posting',
                'target_id' => $job->id,
                'details' => [],
            ]);
        }

        $bin->delete();
        session()->flash('success', 'Job restored successfully.');
    }

    public function permanentDelete(string $binId): void
    {
        $bin = JobBinModel::findOrFail($binId);
        $job = JobPosting::withTrashed()->find($bin->job_id);

        if ($job) {
            $job->forceDelete();
            AdminActionLog::create([
                'admin_id' => Auth::guard('admin')->id(),
                'action' => 'job_permanently_deleted',
                'target_type' => 'job_posting',
                'target_id' => $bin->job_id,
                'details' => ['job_title' => $bin->original_data['job_title'] ?? 'Unknown'],
            ]);
        }

        $bin->delete();
        session()->flash('success', 'Job permanently deleted.');
    }

    public function emptyBin(): void
    {
        $bins = JobBinModel::all();
        foreach ($bins as $bin) {
            $job = JobPosting::withTrashed()->find($bin->job_id);
            if ($job) $job->forceDelete();
            $bin->delete();
        }
        AdminActionLog::create([
            'admin_id' => Auth::guard('admin')->id(),
            'action' => 'job_bin_emptied',
            'target_type' => 'job_bin',
            'target_id' => null,
            'details' => ['count' => $bins->count()],
        ]);
        session()->flash('success', 'Bin emptied. ' . $bins->count() . ' jobs permanently deleted.');
    }

    public function render()
    {
        $query = JobBinModel::latest();

        if ($this->search) {
            $query->whereRaw("JSON_EXTRACT(original_data, '$.job_title') LIKE ?", ["%{$this->search}%"]);
        }

        return view('livewire.admin.jobs.job-bin', [
            'bins' => $query->paginate(20),
            'totalCount' => JobBinModel::count(),
        ]);
    }
}
