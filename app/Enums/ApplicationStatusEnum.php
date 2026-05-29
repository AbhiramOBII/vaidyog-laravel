<?php

namespace App\Enums;

enum ApplicationStatusEnum: string
{
    case Applied = 'applied';
    case Reviewed = 'reviewed';
    case Shortlisted = 'shortlisted';
    case Interviewed = 'interviewed';
    case Offered = 'offered';
    case Rejected = 'rejected';
    case Pending = 'pending';
    case Scheduled = 'scheduled';

    public function label(): string
    {
        return match ($this) {
            self::Applied => 'Applied',
            self::Reviewed => 'Reviewed',
            self::Shortlisted => 'Shortlisted',
            self::Interviewed => 'Interviewed',
            self::Offered => 'Offer Extended',
            self::Rejected => 'Rejected',
            self::Pending => 'Pending',
            self::Scheduled => 'Scheduled',
        };
    }

    public function isTerminal(): bool
    {
        return in_array($this, [self::Offered, self::Rejected]);
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Applied => 'blue',
            self::Reviewed => 'indigo',
            self::Shortlisted => 'violet',
            self::Interviewed => 'amber',
            self::Offered => 'green',
            self::Rejected => 'red',
            self::Pending => 'gray',
            self::Scheduled => 'teal',
        };
    }

    public function getBadgeClasses(): string
    {
        return match ($this) {
            self::Applied => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300',
            self::Reviewed => 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-300',
            self::Shortlisted => 'bg-violet-100 text-violet-700 dark:bg-violet-900/30 dark:text-violet-300',
            self::Interviewed => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300',
            self::Offered => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300',
            self::Rejected => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300',
            self::Pending => 'bg-gray-100 text-gray-700 dark:bg-gray-700/30 dark:text-gray-300',
            self::Scheduled => 'bg-teal-100 text-teal-700 dark:bg-teal-900/30 dark:text-teal-300',
        };
    }
}
