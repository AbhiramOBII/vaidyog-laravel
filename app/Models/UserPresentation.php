<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserPresentation extends Model
{
    protected $fillable = [
        'user_id', 'title', 'event_name', 'event_date', 'location', 'description',
    ];

    protected function casts(): array
    {
        return ['event_date' => 'date'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
