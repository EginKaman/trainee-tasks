<?php

declare(strict_types=1);

namespace App\Services\Processing\Validator\Fields;

use App\Services\Processing\Validator\Error;
use League\ISO3166\Exception\{DomainException, OutOfBoundsException};
use League\ISO3166\ISO3166;

class CurrencyCode
{
    public string $secondValue = '';
    public string $secondField = 'country';
    public bool $break = false;

    /**
     * @param string $value
     * @param int $line
     */
    public function correct($value, $line): bool|Error
    {
        if (!preg_match('/^[A-Z]{3}$/', $value)) {
            $this->break = true;

            return new Error('Invalid currencyCode format. Must follow the ISO 4217', $line);
        }

        return true;
    }

    /**
     * @param string $value
     * @param int $line
     */
    public function equal($value, $line): bool|Error
    {
        try {
            $data = (new ISO3166())->alpha3($this->secondValue);
        } catch (DomainException | OutOfBoundsException $exception) {
            $this->break = true;

            return false;
        }
        if (!in_array($value, $data['currency'], true)) {
            return new Error("Invalid currencyCode {$value} for country {$this->secondValue}", $line);
        }

        return true;
    }
}
