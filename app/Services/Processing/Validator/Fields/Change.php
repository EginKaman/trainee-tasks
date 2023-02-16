<?php

declare(strict_types=1);

namespace App\Services\Processing\Validator\Fields;

use App\Services\Processing\Validator\Error;

class Change
{
    public string $secondValue;
    public string $secondField = 'rate';
    public bool $break = false;

    public function correct(string $value, int $line): true|Error
    {
        if (!is_numeric($value)) {
            $this->break = true;

            return new Error('Rate must be decimal', $line);
        }

        return true;
    }

    public function min(string $value, int $line): true|Error
    {
        if ((float) $value <= 0) {
            return new Error('Change must be greater than 0', $line);
        }

        return true;
    }

    public function less(string $value, int $line): true|Error
    {
        if ((float) $value >= (float) $this->secondValue) {
            return new Error('Change must be smaller than rate', $line);
        }

        return true;
    }

    public function max(string $value, int $line): true|Error
    {
        if ((float) $value > 1000000000) {
            return new Error('Change must be smaller than 1 000 000 000', $line);
        }

        return true;
    }
}
