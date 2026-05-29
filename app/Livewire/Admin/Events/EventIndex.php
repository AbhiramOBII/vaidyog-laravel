<?php

namespace App\Livewire\Admin\Events;

use App\Models\Event;
use App\Models\EventCategory;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.admin', ['pageTitle' => 'Events'])]
class EventIndex extends Component
{
    use WithPagination;

    #[Url] public string $search = '';
    #[Url] public string $status = '';
    #[Url] public string $categoryId = '';
    #[Url] public string $eventType = '';

    public function updatedSearch(): void { $this->resetPage(); }
    public function updatedStatus(): void { $this->resetPage(); }
    public function updatedCategoryId(): void { $this->resetPage(); }
    public function updatedEventType(): void { $this->resetPage(); }

    public function toggleStatus(int $id): void
    {
        $event = Event::findOrFail($id);
        $newStatus = $event->status === 'published' ? 'draft' : 'published';
        $event->update([
            'status' => $newStatus,
            'published_at' => $newStatus === 'published' ? now() : null,
        ]);
    }

    public function delete(int $id): void
    {
        Event::findOrFail($id)->delete();
    }

    public function render()
    {
        $query = Event::with('category');

        if ($this->search) {
            $query->where('title', 'like', "%{$this->search}%");
        }
        if ($this->status) {
            $query->where('status', $this->status);
        }
        if ($this->categoryId) {
            $query->where('category_id', $this->categoryId);
        }
        if ($this->eventType) {
            $query->where('event_type', $this->eventType);
        }

        return view('livewire.admin.events.event-index', [
            'events' => $query->latest()->paginate(20),
            'categories' => EventCategory::active()->pluck('title', 'id'),
        ]);
    }
}
