<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Designation extends Model
{
    protected $fillable = ['name', 'category', 'is_active', 'sort_order'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function subDesignations(): HasMany
    {
        return $this->hasMany(SubDesignation::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
