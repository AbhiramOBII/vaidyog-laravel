<?php

namespace App\Livewire\JobSeeker\Profile\SubModels;

use App\Models\UserLanguage;
use Livewire\Component;

class LanguageManager extends Component
{
    public bool $showForm = false;
    public ?int $editingId = null;

    public string $name = '';
    public string $proficiency = 'intermediate';
    public bool $can_read = false;
    public bool $can_write = false;
    public bool $can_speak = false;

    protected function rules(): array
    {
        return [
            'name' => 'required|max:60',
            'proficiency' => 'required|in:beginner,intermediate,fluent,native',
        ];
    }

    public function openForm(?int $id = null): void
    {
        if ($id) {
            $lang = UserLanguage::where('user_id', auth()->id())->findOrFail($id);
            $this->editingId = $id;
            $this->name = $lang->name;
            $this->proficiency = $lang->proficiency;
            $this->can_read = $lang->can_read;
            $this->can_write = $lang->can_write;
            $this->can_speak = $lang->can_speak;
        } else {
            $this->resetForm();
        }
        $this->showForm = true;
    }

    public function save(): void
    {
        $this->validate();

        if (!$this->can_read && !$this->can_write && !$this->can_speak) {
            $this->addError('can_read', 'Select at least one: Read, Write, or Speak.');
            return;
        }

        $count = UserLanguage::where('user_id', auth()->id())->count();
        if (!$this->editingId && $count >= 10) {
            $this->addError('name', 'Maximum 10 languages allowed.');
            return;
        }

        UserLanguage::updateOrCreate(
            ['id' => $this->editingId, 'user_id' => auth()->id()],
            [
                'user_id' => auth()->id(),
                'name' => $this->name,
                'proficiency' => $this->proficiency,
                'can_read' => $this->can_read,
                'can_write' => $this->can_write,
                'can_speak' => $this->can_speak,
            ]
        );

        $this->resetForm();
        $this->showForm = false;
    }

    public function delete(int $id): void
    {
        UserLanguage::where('user_id', auth()->id())->where('id', $id)->delete();
    }

    public function cancel(): void
    {
        $this->resetForm();
        $this->showForm = false;
    }

    private function resetForm(): void
    {
        $this->editingId = null;
        $this->name = '';
        $this->proficiency = 'intermediate';
        $this->can_read = false;
        $this->can_write = false;
        $this->can_speak = false;
        $this->resetValidation();
    }

    public function render()
    {
        $languages = UserLanguage::where('user_id', auth()->id())->latest()->get();
        return view('livewire.job-seeker.profile.sub-models.language-manager', compact('languages'));
    }
}
