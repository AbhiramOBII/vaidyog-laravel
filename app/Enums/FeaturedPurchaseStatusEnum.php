<?php

namespace App\Enums;

enum FeaturedPurchaseStatusEnum: string
{
    case PendingPayment = 'pending_payment';
    case Active = 'active';
    case Expired = 'expired';

    public function label(): string
    {
        return match ($this) {
            self::PendingPayment => 'Pending Payment',
            self::Active => 'Active',
            self::Expired => 'Expired',
        };
    }

    public function getBadgeClasses(): string
    {
        return match ($this) {
            self::PendingPayment => 'bg-amber-100 text-amber-700',
            self::Active => 'bg-green-100 text-green-700',
            self::Expired => 'bg-neutral-100 text-neutral-600',
        };
    }
}
