<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;
use Throwable;

class UnknownProcessingException extends Exception
{
    public function __construct($message = null, $code = null, ?Throwable $previous = null)
    {
        parent::__construct($message ?? 'Unknown processing.', 0, $previous);

        $this->code = $code ?: 0;
    }
}
