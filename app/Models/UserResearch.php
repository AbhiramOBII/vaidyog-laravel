<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserResearch extends Model
{
    protected $table = 'user_researches';

    protected $fillable = [
        'user_id', 'title', 'institution', 'published_date', 'url', 'description',
    ];

    protected function casts(): array
    {
        return ['published_date' => 'date'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
