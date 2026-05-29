<?php

namespace App\Enums;

enum PaymentStatusEnum: string
{
    case Pending = 'pending';
    case Completed = 'completed';
    case Failed = 'failed';
    case Refunded = 'refunded';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pending',
            self::Completed => 'Completed',
            self::Failed => 'Failed',
            self::Refunded => 'Refunded',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Pending => 'amber',
            self::Completed => 'green',
            self::Failed => 'red',
            self::Refunded => 'blue',
        };
    }

    public function getBadgeClasses(): string
    {
        return match ($this) {
            self::Pending => 'bg-amber-100 text-amber-700',
            self::Completed => 'bg-green-100 text-green-700',
            self::Failed => 'bg-red-100 text-red-700',
            self::Refunded => 'bg-blue-100 text-blue-700',
        };
    }
}
