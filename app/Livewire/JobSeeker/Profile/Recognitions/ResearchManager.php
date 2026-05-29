<?php

namespace App\Livewire\JobSeeker\Profile\Recognitions;

use App\Models\UserResearch;
use Livewire\Component;

class ResearchManager extends Component
{
    public bool $showForm = false;
    public ?int $editingId = null;
    public string $title = '';
    public string $institution = '';
    public ?string $published_date = null;
    public string $url = '';
    public string $description = '';

    protected function rules(): array
    {
        return ['title' => 'required|max:200', 'institution' => 'nullable|max:150', 'published_date' => 'nullable|date', 'url' => 'nullable|url|max:255', 'description' => 'nullable|max:500'];
    }

    public function openForm(?int $id = null): void
    {
        if ($id) {
            $item = UserResearch::where('user_id', auth()->id())->findOrFail($id);
            $this->editingId = $id; $this->title = $item->title; $this->institution = $item->institution ?? ''; $this->published_date = $item->published_date?->format('Y-m-d'); $this->url = $item->url ?? ''; $this->description = $item->description ?? '';
        } else { $this->resetForm(); }
        $this->showForm = true;
    }

    public function save(): void
    {
        $this->validate();
        UserResearch::updateOrCreate(
            ['id' => $this->editingId, 'user_id' => auth()->id()],
            ['user_id' => auth()->id(), 'title' => $this->title, 'institution' => $this->institution ?: null, 'published_date' => $this->published_date ?: null, 'url' => $this->url ?: null, 'description' => $this->description ?: null]
        );
        $this->resetForm(); $this->showForm = false;
    }

    public function delete(int $id): void { UserResearch::where('user_id', auth()->id())->where('id', $id)->delete(); }
    public function cancel(): void { $this->resetForm(); $this->showForm = false; }

    private function resetForm(): void
    {
        $this->editingId = null; $this->title = ''; $this->institution = ''; $this->published_date = null; $this->url = ''; $this->description = '';
        $this->resetValidation();
    }

    public function render()
    {
        $items = UserResearch::where('user_id', auth()->id())->latest()->get();
        return view('livewire.job-seeker.profile.recognitions.research-manager', compact('items'));
    }
}
