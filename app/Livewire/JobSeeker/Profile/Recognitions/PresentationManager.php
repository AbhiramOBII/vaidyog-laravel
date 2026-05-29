<?php

namespace App\Livewire\JobSeeker\Profile\Recognitions;

use App\Models\UserPresentation;
use Livewire\Component;

class PresentationManager extends Component
{
    public bool $showForm = false;
    public ?int $editingId = null;
    public string $title = '';
    public string $event_name = '';
    public ?string $event_date = null;
    public string $location = '';
    public string $description = '';

    protected function rules(): array
    {
        return [
            'title' => 'required|max:200',
            'event_name' => 'nullable|max:150',
            'event_date' => 'nullable|date',
            'location' => 'nullable|max:100',
            'description' => 'nullable|max:500',
        ];
    }

    public function openForm(?int $id = null): void
    {
        if ($id) {
            $item = UserPresentation::where('user_id', auth()->id())->findOrFail($id);
            $this->editingId = $id;
            $this->title = $item->title;
            $this->event_name = $item->event_name ?? '';
            $this->event_date = $item->event_date?->format('Y-m-d');
            $this->location = $item->location ?? '';
            $this->description = $item->description ?? '';
        } else { $this->resetForm(); }
        $this->showForm = true;
    }

    public function save(): void
    {
        $this->validate();
        UserPresentation::updateOrCreate(
            ['id' => $this->editingId, 'user_id' => auth()->id()],
            ['user_id' => auth()->id(), 'title' => $this->title, 'event_name' => $this->event_name ?: null, 'event_date' => $this->event_date ?: null, 'location' => $this->location ?: null, 'description' => $this->description ?: null]
        );
        $this->resetForm(); $this->showForm = false;
    }

    public function delete(int $id): void { UserPresentation::where('user_id', auth()->id())->where('id', $id)->delete(); }
    public function cancel(): void { $this->resetForm(); $this->showForm = false; }

    private function resetForm(): void
    {
        $this->editingId = null; $this->title = ''; $this->event_name = ''; $this->event_date = null; $this->location = ''; $this->description = '';
        $this->resetValidation();
    }

    public function render()
    {
        $items = UserPresentation::where('user_id', auth()->id())->latest()->get();
        return view('livewire.job-seeker.profile.recognitions.presentation-manager', compact('items'));
    }
}
