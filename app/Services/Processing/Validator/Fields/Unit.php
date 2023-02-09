<?php

declare(strict_types=1);

namespace App\Services\Processing\Validator\Fields;

use App\Services\Processing\Validator\Error;

class Unit
{
    public bool $break = false;

    public function number(string|int $value, int $line): true|Error
    {
        if (!preg_match('/^\d+$/', $value)) {
            $this->break = true;

            return new Error('The unit must be whole number.', $line);
        }

        return true;
    }

    public function max(string $value, int $line): true|Error
    {
        if ((int) $value > 1000000) {
            return new Error('The unit must be smaller than 1000000.', $line);
        }

        return true;
    }

    public function min(string $value, int $line): true|Error
    {
        if ((int) $value < 1) {
            return new Error('The unit must be greater than 1.', $line);
        }

        return true;
    }
}
