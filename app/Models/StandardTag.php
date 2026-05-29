<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StandardTag extends Model
{
    protected $fillable = [
        'type',
        'name',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    // Type constants
    const TYPE_KEY_SKILL = 'key_skill';
    const TYPE_EDUCATIONAL_REQUIREMENT = 'educational_requirement';
    const TYPE_MEDICAL_QUALIFICATION = 'medical_qualification';
    const TYPE_CERTIFICATION = 'certification';

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    public static function getByType(string $type): array
    {
        return static::active()->ofType($type)->ordered()->pluck('name')->toArray();
    }
}
