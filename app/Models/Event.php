<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Event extends Model
{
    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'short_description',
        'full_description',
        'event_type',
        'online_link',
        'venue',
        'event_date',
        'status',
        'thumbnail_image',
        'meta_title',
        'meta_description',
        'published_at',
    ];

    protected $casts = [
        'event_date' => 'datetime',
        'published_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $event) {
            if (empty($event->slug)) {
                $event->slug = Str::slug($event->title) ?: 'event-' . Str::random(8);
            }
        });

        static::updating(function (self $event) {
            if ($event->isDirty('title') && !$event->isDirty('slug')) {
                $event->slug = Str::slug($event->title);
            }
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(EventCategory::class, 'category_id');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('event_date', '>=', now());
    }
}
