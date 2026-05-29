<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Feedback extends Model
{
    protected $table = 'feedbacks';

    protected $fillable = [
        'user_id',
        'feedback',
        'name',
        'user_type',
        'read_status',
    ];

    protected $casts = [
        'read_status' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeUnread($query)
    {
        return $query->where('read_status', false);
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('user_type', $type);
    }
}
