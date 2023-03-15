<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;
use Throwable;

class UnknownPaymentMethodException extends Exception
{
    public function __construct(?string $message = null, ?int $code = null, ?Throwable $previous = null)
    {
        parent::__construct($message ?? 'Unknown payment method.', 0, $previous);

        $this->code = $code ?: 0;
    }
}
