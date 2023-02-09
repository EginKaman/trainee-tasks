<?php

declare(strict_types=1);

namespace App\Services\Processing\Validator\Fields;

use App\Services\Processing\Validator\Error;
use Carbon\Exceptions\InvalidArgumentException;
use Illuminate\Support\Facades\Date;

class LastUpdate
{
    public bool $break = false;

    public function correct(string $value, int $line): bool|Error
    {
        try {
            $lastUpdate = Date::createFromFormat('Y-m-d', $value);
        } catch (InvalidArgumentException $exception) {
            $this->break = true;

            return new Error($exception->getMessage(), $line);
        }
        if (!$lastUpdate) {
            $this->break = true;

            return new Error('Invalid date format, correct date format is "Y-m-d"', $line);
        }

        return true;
    }

    public function max(string $value, int $line): bool|Error
    {
        $lastUpdate = Date::createFromFormat('Y-m-d', $value)->startOfDay();
        $today = Date::today();
        if ($lastUpdate > Date::today()) {
            return new Error("The value '{$lastUpdate}' must be smaller than '{$today}'", $line);
        }

        return true;
    }

    public function min(string $value, int $line): bool|Error
    {
        $lastUpdate = Date::createFromFormat('Y-m-d', $value);
        $minDate = Date::createFromFormat('Y-m-d', '1970-01-01');
        if ($lastUpdate < Date::createFromFormat('Y-m-d', '1970-01-01')) {
            return new Error("The value '{$lastUpdate}' must be greater than '{$minDate}'", $line);
        }

        return true;
    }
}
