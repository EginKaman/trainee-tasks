<?php

namespace App\Services\Processing\Validator;

use Illuminate\Contracts\Support\Arrayable;

class ErrorBag
{
    public array $errors = [];

    /**
     * @param Error $error
     * @return $this
     */
    public function add(Error $error): static
    {
        $this->errors[] = $error;
        return $this;
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->errors);
    }

    /**
     * @return bool
     */
    public function isNotEmpty(): bool
    {
        return $this->count() > 0;
    }

    public function errors()
    {
        return $this->errors;
    }
}
