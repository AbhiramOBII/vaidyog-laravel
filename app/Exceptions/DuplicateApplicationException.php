<?php

namespace App\Exceptions;

use Exception;

class DuplicateApplicationException extends Exception
{
    public function __construct()
    {
        parent::__construct('You have already applied to this job.');
    }
}
