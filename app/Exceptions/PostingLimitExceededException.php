<?php

namespace App\Exceptions;

use RuntimeException;

class PostingLimitExceededException extends RuntimeException
{
    protected $code = 422;

    public function __construct(string $message = 'You have reached your monthly job posting limit. Upgrade your plan.')
    {
        parent::__construct($message, $this->code);
    }
}
