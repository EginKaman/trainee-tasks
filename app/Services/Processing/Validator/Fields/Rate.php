<?php

declare(strict_types=1);

namespace App\Services\Processing\Validator\Fields;

use App\Services\Processing\Validator\Error;

class Rate
{
    public string $secondValue;
    public string $secondField = 'change';

    public bool $break = false;

    public function correct($value, $line): bool|Error
    {
        if (!is_numeric($value)) {
            $this->break = true;

            return new Error('Rate must be decimal', $line);
        }

        return true;
    }

    public function min($value, $line): bool|Error
    {
        if ((int) $value < 1) {
            return new Error('Rate must be greater than 1 000 000 000.', $line);
        }

        return true;
    }

    public function max($value, $line): bool|Error
    {
        if ((int) $value > 1000000000) {
            return new Error('Rate must be smaller than 1 000 000 000.', $line);
        }

        return true;
    }

    public function greater($value, $line): bool|Error
    {
        if ((float) $value < (float) $this->secondValue) {
            return new Error('Rate must be greater than change', $line);
        }

        return true;
    }
}
