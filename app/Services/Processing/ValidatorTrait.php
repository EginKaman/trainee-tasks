<?php

namespace App\Services\Processing;

use Illuminate\Support\Facades\Date;
use League\ISO3166\ISO3166;
use LibXMLError;

trait ValidatorTrait
{
    protected array $errors = [];

    protected function lastUpdateValidate(string $lastUpdate): void
    {
        $lastUpdate = Date::createFromFormat('Y-m-d', $lastUpdate);
        if (!$lastUpdate) {
            $error = new LibXMLError();
            $error->message = "Invalid date format";
            $error->line = $this->line;
            $this->errors[] = $error;
        }
        $today = Date::today();
        $minDate = Date::createFromFormat('Y-m-d', '1970-01-01');
        if ($lastUpdate > Date::today()) {
            $error = new LibXMLError();
            $error->message = "The value '{$lastUpdate}' must be smaller than '$today'.";
            $error->line = $this->line;
            $this->errors[] = $error;
        }
        if ($lastUpdate < $minDate) {
            $error = new LibXMLError();
            $error->message = "The value '{$lastUpdate}' must be greater than '$minDate'.";
            $error->line = $this->line;
            $this->errors[] = $error;
        }
    }

    protected function nameValidate(string $name): void
    {
        if (!preg_match('/[A-Za-z ]{2,60}/', $name)) {
            $error = new LibXMLError();
            $error->message = "The name must be smaller include only [A-Za-z ] symbols and between 2 to 60 letters.";
            $error->line = $this->line;
            $this->errors[] = $error;
        }
    }

    protected function unitValidate(string|int $unit): void
    {
        if (is_int($unit)) {
            $error = new LibXMLError();
            $error->message = "The unit must be whole number.";
            $error->line = $this->line;
            $this->errors[] = $error;
        }
        if ((int)$unit > 1000000) {
            $error = new LibXMLError();
            $error->message = "The unit c";
            $error->line = $this->line;
            $this->errors[] = $error;
        }
        if ((int)$unit < 1) {
            $error = new LibXMLError();
            $error->message = "The unit must be greater than 1.";
            $error->line = $this->line;
            $this->errors[] = $error;
        }
    }

    protected function countryValidate(string $country): void
    {
        if (!preg_match('/[A-Z]{3}/', $country)) {
            $error = new LibXMLError();
            $error->message = "Invalid country format. Must follow the ISO 3166-1 alpha-3";
            $error->line = $this->line;
            $this->errors[] = $error;
        }
    }

    protected function currencyCodeValidate(string $currencyCode, string $country): void
    {
        if (!preg_match('/[A-Z]{3}/', $currencyCode)) {
            $error = new LibXMLError();
            $error->message = "Invalid currencyCode format. Must follow the ISO 4217";
            $error->line = $this->line;
            $this->errors[] = $error;
        }
        $data = (new ISO3166())->alpha3($country);
        if (!in_array($currencyCode, $data['currency'], true)) {
            $error = new LibXMLError();
            $error->message = "Invalid currencyCode {$currencyCode} for country {$country}";
            $error->line = $this->line;
            $this->errors[] = $error;
        }
    }

    protected function rateChangeValidate($rate, $change): void
    {
        if (!is_numeric($rate)) {
            $error = new LibXMLError();
            $error->message = "Rate must be decimal";
            $error->line = $this->line;
            $this->errors[] = $error;
        }
        if (!is_numeric($change)) {
            $error = new LibXMLError();
            $error->message = "Change must be decimal";
            $error->line = $this->line;
            $this->errors[] = $error;
        }
        if ((float)$rate < (float)$change) {
            $error = new LibXMLError();
            $error->message = "Rate must be greater than change";
            $error->line = $this->line;
            $this->errors[] = $error;
        }
        if ((float)$change > (float)$rate) {
            $error = new LibXMLError();
            $error->message = "Change must be smaller than rate";
            $error->line = $this->line;
            $this->errors[] = $error;
        }
    }
}
