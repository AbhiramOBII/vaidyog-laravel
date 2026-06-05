<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class BlogCategory extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'parent_id',
        'short_description',
        'full_description',
        'status',
        'thumbnail_image',
        'meta_title',
        'meta_description',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->title) ?: 'category-' . Str::random(8);
            }
        });

        static::updating(function (self $category) {
            if ($category->isDirty('title') && !$category->isDirty('slug')) {
                $category->slug = Str::slug($category->title);
            }
        });
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function blogs(): HasMany
    {
        return $this->hasMany(Blog::class, 'category_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeParents($query)
    {
        return $query->whereNull('parent_id');
    }
}
