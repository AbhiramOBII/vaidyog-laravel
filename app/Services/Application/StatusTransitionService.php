<?php

namespace App\Services\Application;

use App\Exceptions\InvalidStatusTransitionException;

class StatusTransitionService
{
    private const TRANSITIONS = [
        'applied' => ['reviewed', 'shortlisted', 'rejected'],
        'reviewed' => ['shortlisted', 'interviewed', 'rejected'],
        'shortlisted' => ['interviewed', 'rejected'],
        'interviewed' => ['offered', 'rejected', 'scheduled'],
        'scheduled' => ['interviewed', 'offered', 'rejected'],
        'offered' => [],
        'rejected' => [],
        'pending' => ['reviewed', 'shortlisted', 'rejected'],
    ];

    public function getAllowedNextStatuses(string $currentStatus): array
    {
        return self::TRANSITIONS[$currentStatus] ?? [];
    }

    /**
     * @throws InvalidStatusTransitionException
     */
    public function validateTransition(string $from, string $to): void
    {
        $allowed = $this->getAllowedNextStatuses($from);

        if (!in_array($to, $allowed)) {
            throw new InvalidStatusTransitionException($from, $to);
        }
    }
}
