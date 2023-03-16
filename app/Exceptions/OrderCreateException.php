<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;
use Throwable;

class OrderCreateException extends Exception
{
    public function __construct(?string $message = null, ?int $code = null, ?Throwable $previous = null)
    {
        parent::__construct($message ?? "Order didn't created.", 0, $previous);

        $this->code = $code ?: 0;
    }
}
