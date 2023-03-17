<?php

declare(strict_types=1);

namespace App\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

class UnknownPaymentMethodException extends HttpException
{
    public function __construct(
        ?string $message = null,
        ?\Throwable $previous = null,
        array $headers = [],
        int $code = 0
    ) {
        parent::__construct(500, $message ?? 'Unknown payment method.', $previous, $headers, $code);
    }
}
