<?php

declare(strict_types=1);

namespace App\Services\Processing\Validator\Fields;

use App\Services\Processing\Validator\Error;
use Illuminate\Support\Str;

class Name
{
    public string $secondField = 'currencyCode';
    public string $secondValue = '';
    public bool $break = false;

    protected array $currencies = [
        'UAH' => 'hryvnia',
        'USD' => 'dollar',
        'EUR' => 'euro',
        'GBP' => 'pound sterling',
        'JPY' => 'yen',
        'CHF' => 'franc',
        'CNY' => 'renminbi',
        'CAD' => 'canadian dollar',
        'GEL' => 'lari',
        'PHP' => 'peso',
        'RUB' => 'ruble',
    ];

    public function correct(string $value, int $line): bool|Error
    {
        if (!preg_match('/^[A-Za-z ]{2,60}$/', $value)) {
            $this->break = true;

            return new Error(
                'The name must be smaller include only latin symbols, space and between 2 to 60 letters.',
                $line
            );
        }

        return true;
    }

    public function exist(string $value, int $line): bool|Error
    {
        if (
            (!isset($this->currencies[$this->secondValue])
            || Str::lower($this->currencies[$this->secondValue]) !== Str::lower($value))
        ) {
            return new Error('The name must be related to currencyCode.', $line);
        }

        return true;
    }
}
