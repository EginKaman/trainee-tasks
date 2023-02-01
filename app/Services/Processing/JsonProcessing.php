<?php

namespace App\Services\Processing;

use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use League\ISO3166\ISO3166;
use LibXMLError;
use RamosHenrique\JsonSchemaValidator\JsonSchemaValidator;
use RamosHenrique\JsonSchemaValidator\JsonSchemaValidatorException;
use Swaggest\JsonSchema\Schema;

class JsonProcessing implements ProcessingInterface
{
    private string $schema;
    private JsonSchemaValidator $jsonSchemaValidator;

    public function __construct(JsonSchemaValidator $jsonSchemaValidator)
    {
        $this->schema = resource_path('schemas/schema.json');
        $this->jsonSchemaValidator = $jsonSchemaValidator;
    }

    /**
     * @param string $path
     * @return bool|array
     * @throws JsonSchemaValidatorException
     */
    public function validate(string $path): bool|array
    {
        $schema = Schema::import(json_decode(file_get_contents($this->schema)));
        $json = $schema->in(json_decode(file_get_contents($path)));
        $result = true;
        $errors = [];
        foreach ($json as $exrate) {
            $lastUpdate = Date::createFromFormat('Y-m-d', $exrate->lastUpdate);
            $today = Date::today();
            $minDate = Date::createFromFormat('Y-m-d', '1970-01-01');
            if (!$lastUpdate) {
                $error = new LibXMLError();
                $error->message = "Invalid date format";
                $error->file = $path;
                $error->line = 1;
                $error->code = 1836;
                $error->column = 0;
                $error->level = 2;
                $errors[] = $error;
            }
            if ($lastUpdate > Date::today()) {
                $error = new LibXMLError();
                $error->message = "The value '{$exrate->lastUpdate}' must be smaller than '$today'.";
                $error->file = $path;
                $error->line = 1;
                $error->code = 1835;
                $error->column = 0;
                $error->level = 2;
                $errors[] = $error;
            }
            if ($lastUpdate < $minDate) {
                $error = new LibXMLError();
                $error->message = "The value '{$exrate->lastUpdate}' must be greater than '$minDate'.";
                $error->file = $path;
                $error->line = 1;
                $error->code = 1835;
                $error->column = 0;
                $error->level = 2;
                $errors[] = $error;
            }
            foreach ($exrate->currency as $currency) {
                if (Str::length($currency->name) > 60) {
                    $error = new LibXMLError();
                    $error->message = "The name must be smaller than 60 letters.";
                    $error->file = $path;
                    $error->line = 1;
                    $error->code = 1835;
                    $error->column = 0;
                    $error->level = 2;
                    $errors[] = $error;
                }
                if (Str::length($currency->name) <= 2) {
                    $error = new LibXMLError();
                    $error->message = "The name must be greater than 2 letters.";
                    $error->file = $path;
                    $error->line = 1;
                    $error->code = 1835;
                    $error->column = 0;
                    $error->level = 2;
                    $errors[] = $error;
                }
                if (is_int($currency->unit)) {
                    $error = new LibXMLError();
                    $error->message = "The unit must be whole number.";
                    $error->file = $path;
                    $error->line = 1;
                    $error->code = 1835;
                    $error->column = 0;
                    $error->level = 2;
                    $errors[] = $error;
                }
                if ((int)$currency->unit > 1000000) {
                    $error = new LibXMLError();
                    $error->message = "The unit must be smaller than 1000000.";
                    $error->file = $path;
                    $error->line = 1;
                    $error->code = 1835;
                    $error->column = 0;
                    $error->level = 2;
                    $errors[] = $error;
                }
                if ((int)$currency->unit < 1) {
                    $error = new LibXMLError();
                    $error->message = "The unit must be greater than 1.";
                    $error->file = $path;
                    $error->line = 1;
                    $error->code = 1835;
                    $error->column = 0;
                    $error->level = 2;
                    $errors[] = $error;
                }
                if ((int)$currency->unit < 1) {
                    $error = new LibXMLError();
                    $error->message = "The unit must be greater than 1.";
                    $error->file = $path;
                    $error->line = 1;
                    $error->code = 1835;
                    $error->column = 0;
                    $error->level = 2;
                    $errors[] = $error;
                }
                if (!preg_match('/[A-Z]{3}/', $currency->country)) {
                    $error = new LibXMLError();
                    $error->message = "Invalid country format";
                    $error->file = $path;
                    $error->line = 1;
                    $error->code = 1836;
                    $error->column = 0;
                    $error->level = 2;
                    $errors[] = $error;
                }
                if (!preg_match('/[A-Z]{3}/', $currency->currencyCode)) {
                    $error = new LibXMLError();
                    $error->message = "Invalid currencyCode format";
                    $error->file = $path;
                    $error->line = 1;
                    $error->code = 1836;
                    $error->column = 0;
                    $error->level = 2;
                    $errors[] = $error;
                }
                $data = (new ISO3166())->alpha3((string)$currency->country);
                if (!in_array((string)$currency->currencyCode, $data['currency'], true)) {
                    $error = new LibXMLError();
                    $error->message = "Invalid currencyCode {$currency->currencyCode} for country {$data['name']}";
                    $error->file = $path;
                    $error->line = 1;
                    $error->code = 1836;
                    $error->column = 0;
                    $error->level = 2;
                    $errors[] = $error;
                }
                if ((float)$currency->rate < (float)$currency->change) {
                    $error = new LibXMLError();
                    $error->message = "Rate must be greater than change";
                    $error->file = $path;
                    $error->line = 1;
                    $error->code = 1836;
                    $error->column = 0;
                    $error->level = 2;
                    $errors[] = $error;
                }
                if ((float)$currency->change > (float)$currency->rate) {
                    $error = new LibXMLError();
                    $error->message = "Change must be smaller than rate";
                    $error->file = $path;
                    $error->line = 1;
                    $error->code = 1836;
                    $error->column = 0;
                    $error->level = 2;
                    $errors[] = $error;
                }
            }
        }
        return $result ?: $errors;
    }

    public function read($file)
    {
        // TODO: Implement read() method.
    }
}
