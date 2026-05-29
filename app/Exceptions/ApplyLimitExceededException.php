<?php

namespace App\Exceptions;

use RuntimeException;

class ApplyLimitExceededException extends RuntimeException
{
    protected $code = 422;

    public function __construct(string $message = 'You have reached your monthly application limit. Upgrade your plan to apply to more jobs.')
    {
        parent::__construct($message, $this->code);
    }
}
