<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhoneOtpSession extends Model
{
    protected $fillable = [
        'phone',
        'otp_code',
        'purpose',
        'expires_at',
        'verified_at',
        'attempts',
        'resend_count',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'verified_at' => 'datetime',
    ];

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }
}
