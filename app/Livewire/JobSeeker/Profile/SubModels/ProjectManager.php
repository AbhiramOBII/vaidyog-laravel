<?php

namespace App\Livewire\JobSeeker\Profile\SubModels;

use App\Models\UserProject;
use Livewire\Component;

class ProjectManager extends Component
{
    public bool $showForm = false;
    public ?int $editingId = null;

    public string $title = '';
    public string $location = '';
    public string $client_name = '';
    public string $status = 'ongoing';
    public ?string $start_date = null;
    public ?string $end_date = null;
    public string $details = '';

    protected function rules(): array
    {
        return [
            'title' => 'required|max:150',
            'location' => 'nullable|max:100',
            'client_name' => 'nullable|max:100',
            'status' => 'required|in:ongoing,completed',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'details' => 'nullable|max:1000',
        ];
    }

    public function openForm(?int $id = null): void
    {
        if ($id) {
            $proj = UserProject::where('user_id', auth()->id())->findOrFail($id);
            $this->editingId = $id;
            $this->title = $proj->title;
            $this->location = $proj->location ?? '';
            $this->client_name = $proj->client_name ?? '';
            $this->status = $proj->status;
            $this->start_date = $proj->start_date?->format('Y-m-d');
            $this->end_date = $proj->end_date?->format('Y-m-d');
            $this->details = $proj->details ?? '';
        } else {
            $this->resetForm();
        }
        $this->showForm = true;
    }

    public function save(): void
    {
        $this->validate();

        UserProject::updateOrCreate(
            ['id' => $this->editingId, 'user_id' => auth()->id()],
            [
                'user_id' => auth()->id(),
                'title' => $this->title,
                'location' => $this->location ?: null,
                'client_name' => $this->client_name ?: null,
                'status' => $this->status,
                'start_date' => $this->start_date ?: null,
                'end_date' => $this->end_date ?: null,
                'details' => $this->details ?: null,
            ]
        );

        $this->resetForm();
        $this->showForm = false;
    }

    public function delete(int $id): void
    {
        UserProject::where('user_id', auth()->id())->where('id', $id)->delete();
    }

    public function cancel(): void
    {
        $this->resetForm();
        $this->showForm = false;
    }

    private function resetForm(): void
    {
        $this->editingId = null;
        $this->title = '';
        $this->location = '';
        $this->client_name = '';
        $this->status = 'ongoing';
        $this->start_date = null;
        $this->end_date = null;
        $this->details = '';
        $this->resetValidation();
    }

    public function render()
    {
        $projects = UserProject::where('user_id', auth()->id())->latest()->get();
        return view('livewire.job-seeker.profile.sub-models.project-manager', compact('projects'));
    }
}
