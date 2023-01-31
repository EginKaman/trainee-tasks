<?php

namespace App\Exceptions;

use Exception;

class UnknownProcessingException extends Exception
{
    public function __construct($message = null, $code = null, Throwable $previous = null)
    {
        parent::__construct($message ?? 'Unknown processing.', 0, $previous);

        $this->code = $code ?: 0;
    }
}
