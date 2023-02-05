<?php

namespace App\Services\Processing\Validator\Fields;

use App\Services\Processing\Validator\Error;
use League\ISO3166\ISO3166;

class CurrencyCode
{
    public string $secondValue = '';
    public string $secondField = 'country';
    /**
     * @var true
     */
    public bool $break = false;

    public function correct($value, $line): bool|Error
    {
        if (!preg_match('/^[A-Z]{3}$/', $value)) {
            $this->break = true;
            return new Error('Invalid currencyCode format. Must follow the ISO 4217', $line);
        }
        return true;
    }

    public function equal($value, $line): bool|Error
    {
        $data = (new ISO3166())->alpha3($this->secondValue);
        if (!in_array($value, $data['currency'], true)) {
            return new Error("Invalid currencyCode {$value} for country {$this->secondValue}", $line);
        }
        return true;
    }
}
