<?php

namespace App\Livewire\JobSeeker\Profile\Recognitions;

use App\Models\UserHonoursAward;
use Livewire\Component;

class HonourManager extends Component
{
    public bool $showForm = false;
    public ?int $editingId = null;
    public string $title = '';
    public string $issuing_body = '';
    public ?string $award_date = null;
    public string $description = '';

    protected function rules(): array
    {
        return ['title' => 'required|max:200', 'issuing_body' => 'nullable|max:150', 'award_date' => 'nullable|date', 'description' => 'nullable|max:500'];
    }

    public function openForm(?int $id = null): void
    {
        if ($id) {
            $item = UserHonoursAward::where('user_id', auth()->id())->findOrFail($id);
            $this->editingId = $id; $this->title = $item->title; $this->issuing_body = $item->issuing_body ?? ''; $this->award_date = $item->award_date?->format('Y-m-d'); $this->description = $item->description ?? '';
        } else { $this->resetForm(); }
        $this->showForm = true;
    }

    public function save(): void
    {
        $this->validate();
        UserHonoursAward::updateOrCreate(
            ['id' => $this->editingId, 'user_id' => auth()->id()],
            ['user_id' => auth()->id(), 'title' => $this->title, 'issuing_body' => $this->issuing_body ?: null, 'award_date' => $this->award_date ?: null, 'description' => $this->description ?: null]
        );
        $this->resetForm(); $this->showForm = false;
    }

    public function delete(int $id): void { UserHonoursAward::where('user_id', auth()->id())->where('id', $id)->delete(); }
    public function cancel(): void { $this->resetForm(); $this->showForm = false; }

    private function resetForm(): void
    {
        $this->editingId = null; $this->title = ''; $this->issuing_body = ''; $this->award_date = null; $this->description = '';
        $this->resetValidation();
    }

    public function render()
    {
        $items = UserHonoursAward::where('user_id', auth()->id())->latest()->get();
        return view('livewire.job-seeker.profile.recognitions.honour-manager', compact('items'));
    }
}
