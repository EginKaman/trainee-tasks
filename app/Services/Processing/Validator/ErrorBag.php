<?php

declare(strict_types=1);

namespace App\Services\Processing\Validator;

class ErrorBag
{
    public array $errors = [];

    /**
     * @return $this
     */
    public function add(Error $error): static
    {
        $this->errors[] = $error;

        return $this;
    }

    public function count(): int
    {
        return count($this->errors);
    }

    public function isNotEmpty(): bool
    {
        return $this->count() > 0;
    }

    public function errors(): array
    {
        return $this->errors;
    }
}
