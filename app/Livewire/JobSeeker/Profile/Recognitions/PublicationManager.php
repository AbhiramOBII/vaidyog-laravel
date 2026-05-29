<?php

namespace App\Livewire\JobSeeker\Profile\Recognitions;

use App\Models\UserPublication;
use Livewire\Component;

class PublicationManager extends Component
{
    public bool $showForm = false;
    public ?int $editingId = null;

    public string $title = '';
    public string $publication_name = '';
    public ?string $published_date = null;
    public string $url = '';
    public string $description = '';

    protected function rules(): array
    {
        return [
            'title' => 'required|max:200',
            'publication_name' => 'nullable|max:150',
            'published_date' => 'nullable|date',
            'url' => 'nullable|url|max:255',
            'description' => 'nullable|max:500',
        ];
    }

    public function openForm(?int $id = null): void
    {
        if ($id) {
            $item = UserPublication::where('user_id', auth()->id())->findOrFail($id);
            $this->editingId = $id;
            $this->title = $item->title;
            $this->publication_name = $item->publication_name ?? '';
            $this->published_date = $item->published_date?->format('Y-m-d');
            $this->url = $item->url ?? '';
            $this->description = $item->description ?? '';
        } else {
            $this->resetForm();
        }
        $this->showForm = true;
    }

    public function save(): void
    {
        $this->validate();
        UserPublication::updateOrCreate(
            ['id' => $this->editingId, 'user_id' => auth()->id()],
            [
                'user_id' => auth()->id(),
                'title' => $this->title,
                'publication_name' => $this->publication_name ?: null,
                'published_date' => $this->published_date ?: null,
                'url' => $this->url ?: null,
                'description' => $this->description ?: null,
            ]
        );
        $this->resetForm();
        $this->showForm = false;
    }

    public function delete(int $id): void
    {
        UserPublication::where('user_id', auth()->id())->where('id', $id)->delete();
    }

    public function cancel(): void { $this->resetForm(); $this->showForm = false; }

    private function resetForm(): void
    {
        $this->editingId = null;
        $this->title = '';
        $this->publication_name = '';
        $this->published_date = null;
        $this->url = '';
        $this->description = '';
        $this->resetValidation();
    }

    public function render()
    {
        $items = UserPublication::where('user_id', auth()->id())->latest()->get();
        return view('livewire.job-seeker.profile.recognitions.publication-manager', compact('items'));
    }
}
