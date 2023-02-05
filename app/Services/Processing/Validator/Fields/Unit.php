<?php

namespace App\Services\Processing\Validator\Fields;

use App\Services\Processing\Validator\Error;

class Unit
{
    public bool $break = false;

    public function number(string|int $value, $line)
    {
        if (!preg_match('/^\d+$/', $value)) {
            $this->break = true;
            return new Error(
                "The unit must be whole number.",
                $line
            );
        }
        return true;
    }

    public function max($value, $line)
    {
        if ((int)$value > 1000000) {
            return new Error(
                "The unit must be smaller than 1000000.",
                $line
            );
        }
        return true;
    }

    public function min($value, $line)
    {
        if ((int)$value < 1) {
            return new Error(
                "The unit must be greater than 1.",
                $line
            );
        }
        return true;
    }
}
