<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupportTicket extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'status',
        'status_dates',
        'comments',
    ];

    protected $casts = [
        'status_dates' => 'array',
        'comments' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function addComment(string $message, string $author, string $role = 'admin'): void
    {
        $comments = $this->comments ?? [];
        $comments[] = [
            'id' => count($comments) + 1,
            'message' => $message,
            'author' => $author,
            'role' => $role,
            'created_at' => now()->toDateTimeString(),
        ];
        $this->update(['comments' => $comments]);
    }

    public function updateStatus(string $status): void
    {
        $dates = $this->status_dates ?? [];
        $dates[$status] = now()->toDateTimeString();
        $this->update([
            'status' => $status,
            'status_dates' => $dates,
        ]);
    }
}
