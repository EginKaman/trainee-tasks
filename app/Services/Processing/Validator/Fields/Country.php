<?php declare(strict_types=1);

namespace App\Services\Processing\Validator\Fields;

use App\Services\Processing\Validator\Error;
use League\ISO3166\ISO3166;

class Country
{

    public string $secondValue;
    public string $secondField = 'currencyCode';
    public bool $break = false;

    /**
     * @param $value
     * @param $line
     * @return bool|Error
     */
    public function correct($value, $line): bool|Error
    {
        if (!preg_match('/^[A-Z]{3}$/', $value)) {
            return new Error('Invalid country format. Must follow the ISO 3166-1 alpha-3', $line);
        }
        return true;
    }

    public function exist($value, $line)
    {
        $data = (new ISO3166())->alpha3($value);
        return false;
    }
}
