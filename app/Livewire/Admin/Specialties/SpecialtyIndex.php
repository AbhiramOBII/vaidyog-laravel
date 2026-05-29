<?php

namespace App\Livewire\Admin\Specialties;

use App\Models\Specialty;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Storage;

#[Layout('components.layouts.admin', ['pageTitle' => 'Specialties'])]
class SpecialtyIndex extends Component
{
    use WithFileUploads, WithPagination;

    public bool $showModal = false;
    public ?int $editingId = null;

    public string $name = '';
    public string $search_term = '';
    public int $sort_order = 0;
    public bool $is_active = true;
    public bool $is_featured = false;
    public $icon = null;
    public ?string $currentIcon = null;

    public function create(): void
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit(int $id): void
    {
        $specialty = Specialty::findOrFail($id);
        $this->editingId = $specialty->id;
        $this->name = $specialty->name;
        $this->search_term = $specialty->search_term ?? '';
        $this->sort_order = $specialty->sort_order;
        $this->is_active = $specialty->is_active;
        $this->is_featured = $specialty->is_featured;
        $this->currentIcon = $specialty->icon_path;
        $this->icon = null;
        $this->showModal = true;
    }

    public function save(): void
    {
        $rules = [
            'name' => 'required|string|max:100',
            'search_term' => 'nullable|string|max:100',
            'sort_order' => 'required|integer|min:0',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ];

        if (!$this->editingId) {
            $rules['icon'] = 'required|file|mimes:svg,png,webp,jpg,jpeg|max:512';
        } else {
            $rules['icon'] = 'nullable|file|mimes:svg,png,webp,jpg,jpeg|max:512';
        }

        $this->validate($rules);

        $data = [
            'name' => $this->name,
            'slug' => Str::slug($this->name),
            'search_term' => $this->search_term ?: Str::slug($this->name),
            'sort_order' => $this->sort_order,
            'is_active' => $this->is_active,
            'is_featured' => $this->is_featured,
        ];

        if ($this->icon) {
            if ($this->currentIcon) {
                Storage::disk('public')->delete($this->currentIcon);
            }
            $data['icon_path'] = $this->icon->store('specialties', 'public');
        }

        if ($this->editingId) {
            Specialty::where('id', $this->editingId)->update($data);
            session()->flash('success', 'Specialty updated.');
        } else {
            Specialty::create($data);
            session()->flash('success', 'Specialty created.');
        }

        $this->showModal = false;
        $this->resetForm();
    }

    public function delete(int $id): void
    {
        $specialty = Specialty::findOrFail($id);
        if ($specialty->icon_path) {
            Storage::disk('public')->delete($specialty->icon_path);
        }
        $specialty->delete();
        session()->flash('success', 'Specialty deleted.');
    }

    public function toggleActive(int $id): void
    {
        $specialty = Specialty::findOrFail($id);
        $specialty->update(['is_active' => !$specialty->is_active]);
    }

    public function toggleFeatured(int $id): void
    {
        $specialty = Specialty::findOrFail($id);
        $specialty->update(['is_featured' => !$specialty->is_featured]);
    }

    private function resetForm(): void
    {
        $this->editingId = null;
        $this->name = '';
        $this->search_term = '';
        $this->sort_order = 0;
        $this->is_active = true;
        $this->is_featured = false;
        $this->icon = null;
        $this->currentIcon = null;
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.admin.specialties.specialty-index', [
            'specialties' => Specialty::ordered()->paginate(20),
        ]);
    }
}
