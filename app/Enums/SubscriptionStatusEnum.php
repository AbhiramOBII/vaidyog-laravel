<?php

namespace App\Enums;

enum SubscriptionStatusEnum: string
{
    case Active = 'active';
    case Expired = 'expired';
    case Cancelled = 'cancelled';
    case PendingPayment = 'pending_payment';

    public function label(): string
    {
        return match ($this) {
            self::Active => 'Active',
            self::Expired => 'Expired',
            self::Cancelled => 'Cancelled',
            self::PendingPayment => 'Pending Payment',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Active => 'green',
            self::Expired => 'neutral',
            self::Cancelled => 'red',
            self::PendingPayment => 'amber',
        };
    }

    public function getBadgeClasses(): string
    {
        return match ($this) {
            self::Active => 'bg-green-100 text-green-700',
            self::Expired => 'bg-neutral-100 text-neutral-600',
            self::Cancelled => 'bg-red-100 text-red-700',
            self::PendingPayment => 'bg-amber-100 text-amber-700',
        };
    }
}
