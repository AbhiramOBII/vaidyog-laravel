<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserHonoursAward extends Model
{
    protected $fillable = [
        'user_id', 'title', 'issuing_body', 'award_date', 'description',
    ];

    protected function casts(): array
    {
        return ['award_date' => 'date'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
