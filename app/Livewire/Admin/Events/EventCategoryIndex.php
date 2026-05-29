<?php

namespace App\Livewire\Admin\Events;

use App\Models\EventCategory;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.admin', ['pageTitle' => 'Event Categories'])]
class EventCategoryIndex extends Component
{
    use WithPagination;

    #[Url] public string $search = '';
    #[Url] public string $status = '';

    public function updatedSearch(): void { $this->resetPage(); }
    public function updatedStatus(): void { $this->resetPage(); }

    public function toggleStatus(int $id): void
    {
        $category = EventCategory::findOrFail($id);
        $category->update([
            'status' => $category->status === 'active' ? 'inactive' : 'active',
        ]);
    }

    public function delete(int $id): void
    {
        EventCategory::findOrFail($id)->delete();
    }

    public function render()
    {
        $query = EventCategory::with('parent');

        if ($this->search) {
            $query->where('title', 'like', "%{$this->search}%");
        }
        if ($this->status) {
            $query->where('status', $this->status);
        }

        return view('livewire.admin.events.event-category-index', [
            'categories' => $query->latest()->paginate(20),
        ]);
    }
}
