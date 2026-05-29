<?php

namespace App\Livewire\Admin\Events;

use App\Models\Event;
use App\Models\EventCategory;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('components.layouts.admin', ['pageTitle' => 'Event'])]
class EventForm extends Component
{
    use WithFileUploads;

    public ?int $eventId = null;

    public string $title = '';
    public string $slug = '';
    public ?int $category_id = null;
    public string $short_description = '';
    public string $full_description = '';
    public string $event_type = 'offline';
    public string $online_link = '';
    public string $venue = '';
    public ?string $event_date = null;
    public string $status = 'draft';
    public $thumbnail_image = null;
    public ?string $existing_thumbnail = null;
    public string $meta_title = '';
    public string $meta_description = '';

    public function mount(?int $event = null): void
    {
        if ($event) {
            $e = Event::findOrFail($event);
            $this->eventId = $e->id;
            $this->title = $e->title;
            $this->slug = $e->slug;
            $this->category_id = $e->category_id;
            $this->short_description = $e->short_description ?? '';
            $this->full_description = $e->full_description ?? '';
            $this->event_type = $e->event_type;
            $this->online_link = $e->online_link ?? '';
            $this->venue = $e->venue ?? '';
            $this->event_date = $e->event_date?->format('Y-m-d\TH:i');
            $this->status = $e->status;
            $this->existing_thumbnail = $e->thumbnail_image;
            $this->meta_title = $e->meta_title ?? '';
            $this->meta_description = $e->meta_description ?? '';
        }
    }

    public function updatedTitle(): void
    {
        if (!$this->eventId) {
            $this->slug = Str::slug($this->title);
        }
    }

    public function save(): void
    {
        $rules = [
            'title' => 'required|max:200',
            'slug' => 'required|max:200|unique:events,slug' . ($this->eventId ? ",{$this->eventId}" : ''),
            'category_id' => 'required|exists:event_categories,id',
            'short_description' => 'nullable|max:500',
            'full_description' => 'nullable',
            'event_type' => 'required|in:online,offline',
            'online_link' => 'nullable|required_if:event_type,online|url|max:500',
            'venue' => 'nullable|required_if:event_type,offline|max:500',
            'event_date' => 'nullable|date',
            'status' => 'required|in:draft,published',
            'thumbnail_image' => 'nullable|image|max:2048',
            'meta_title' => 'nullable|max:200',
            'meta_description' => 'nullable|max:500',
        ];

        $this->validate($rules);

        $data = [
            'title' => $this->title,
            'slug' => $this->slug,
            'category_id' => $this->category_id,
            'short_description' => $this->short_description ?: null,
            'full_description' => $this->full_description ?: null,
            'event_type' => $this->event_type,
            'online_link' => $this->event_type === 'online' ? ($this->online_link ?: null) : null,
            'venue' => $this->event_type === 'offline' ? ($this->venue ?: null) : null,
            'event_date' => $this->event_date ?: null,
            'status' => $this->status,
            'meta_title' => $this->meta_title ?: null,
            'meta_description' => $this->meta_description ?: null,
        ];

        if ($this->thumbnail_image) {
            $data['thumbnail_image'] = $this->thumbnail_image->store('events', 'public');
        }

        if ($this->status === 'published') {
            $data['published_at'] = now();
        }

        if ($this->eventId) {
            Event::findOrFail($this->eventId)->update($data);
            session()->flash('success', 'Event updated successfully.');
        } else {
            Event::create($data);
            session()->flash('success', 'Event created successfully.');
        }

        $this->redirect(route('admin.events.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.events.event-form', [
            'categories' => EventCategory::active()->pluck('title', 'id'),
        ]);
    }
}
