<?php

declare(strict_types=1);

namespace App\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

class AlreadySubscribedException extends HttpException
{
    public function __construct(
        ?string $message = null,
        ?\Throwable $previous = null,
        array $headers = [],
        int $code = 0
    ) {
        parent::__construct(409, $message ?? __('You already have the subscription'), $previous, $headers, $code);
    }
}
