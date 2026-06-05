<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class News extends Model
{
    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'short_description',
        'full_description',
        'status',
        'thumbnail_image',
        'meta_title',
        'meta_description',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $news) {
            if (empty($news->slug)) {
                $news->slug = Str::slug($news->title) ?: 'news-' . Str::random(8);
            }
        });

        static::updating(function (self $news) {
            if ($news->isDirty('title') && !$news->isDirty('slug')) {
                $news->slug = Str::slug($news->title);
            }
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(NewsCategory::class, 'category_id');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }
}
