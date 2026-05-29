<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserAffiliation extends Model
{
    protected $fillable = [
        'user_id', 'organization_name', 'role', 'member_since', 'member_until', 'is_current',
    ];

    protected function casts(): array
    {
        return [
            'member_since' => 'date',
            'member_until' => 'date',
            'is_current' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
