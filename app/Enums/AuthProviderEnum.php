<?php

namespace App\Enums;

enum AuthProviderEnum: string
{
    case Phone = 'phone';
    case Google = 'google';
    case Email = 'email';

    public function label(): string
    {
        return match ($this) {
            self::Phone => 'Phone',
            self::Google => 'Google',
            self::Email => 'Email',
        };
    }
}
