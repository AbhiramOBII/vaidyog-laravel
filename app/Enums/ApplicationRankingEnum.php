<?php

namespace App\Enums;

enum ApplicationRankingEnum: string
{
    case A = 'A';
    case B = 'B';
    case C = 'C';
    case D = 'D';

    public function getLabel(): string
    {
        return match ($this) {
            self::A => 'Platinum Applicant',
            self::B => 'Gold Applicant',
            self::C => 'Silver Applicant',
            self::D => 'Basic Applicant',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::A => 'amber',
            self::B => 'gray',
            self::C => 'orange',
            self::D => 'neutral',
        };
    }

    public function getBadgeClasses(): string
    {
        return match ($this) {
            self::A => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300',
            self::B => 'bg-gray-200 text-gray-700 dark:bg-gray-700/30 dark:text-gray-300',
            self::C => 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-300',
            self::D => 'bg-neutral-100 text-neutral-600 dark:bg-neutral-700/30 dark:text-neutral-400',
        };
    }
}
