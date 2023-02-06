<?php

declare(strict_types=1);

namespace App\Services\Processing\Validator;

class Error
{
    public string $message;
    public int $line;

    public function __construct($message, $line)
    {
        $this->message = $message;
        $this->line = $line;
    }
}
