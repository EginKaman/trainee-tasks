<?php

declare(strict_types=1);

namespace App\Services\Processing\Validator\Fields;

use App\Services\Processing\Validator\Error;

class Change
{
    public string $secondValue;
    public string $secondField = 'rate';
    public bool $break = false;

    public function correct($value, $line)
    {
        if (!is_numeric($value)) {
            $this->break = true;

            return new Error('Rate must be decimal', $line);
        }

        return true;
    }

    public function min($value, $line)
    {
        if ((float) $value <= 0) {
            return new Error('Change must be greater than 0', $line);
        }

        return true;
    }

    public function max($value, $line)
    {
        if ((float) $value > (float) $this->secondValue) {
            return new Error('Change must be smaller than rate', $line);
        }

        return true;
    }
}
