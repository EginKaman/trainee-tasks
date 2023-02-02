<?php

namespace App\Services\Processing;

use Exception;
use Illuminate\Support\Facades\Date;
use LibXMLError;
use RamosHenrique\JsonSchemaValidator\JsonSchemaValidator;
use RamosHenrique\JsonSchemaValidator\JsonSchemaValidatorException;
use Swaggest\JsonSchema\InvalidValue;
use Swaggest\JsonSchema\Schema;

class JsonProcessing implements ProcessingInterface
{
    use ValidatorTrait;

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
     */
    public function validate(string $path): bool|array
    {
        try {
            $schema = Schema::import(json_decode(file_get_contents($this->schema)));
            $json = $schema->in(json_decode(file_get_contents($path)));
        } catch (Exception|InvalidValue $exception) {
            $error = new LibXMLError();
            $error->message = $exception->getMessage();
            $error->line = 1;
            $this->errors[] = $error;
            return false;
        }

        foreach ($json as $exrate) {
            $this->lastUpdateValidate((string)$exrate->lastUpdate);
            foreach ($exrate->currency as $currency) {
                $this->nameValidate((string)$currency->name);
                $this->unitValidate((string)$currency->unit);
                $this->countryValidate((string)$currency->country);
                $this->currencyCodeValidate((string)$currency->currencyCode, (string)$currency->country);
                $this->rateChangeValidate((string)$currency->rate, (string)$currency->change);
            }
        }
        return empty($this->errors) ? true : $this->errors;
    }

    public function read($file)
    {
        // TODO: Implement read() method.
    }

    public function process(string $path)
    {
        $json = json_decode(file_get_contents($path), false);
        foreach ($json as $key => $exrate) {
            if ($key === 0) {
                $exrate->lastUpdate = Date::today()->format('Y-m-d');
            } else {
                $exrate->lastUpdate = Date::createFromFormat('Y-m-d', $json[$key - 1]->lastUpdate)
                    ->subDay()
                    ->format('Y-m-d');
            }
            $exrate->rate = round(random_int(0, 1000000) / mt_getrandmax(), 5);
            $exrate->change = round(random_int(0, $exrate->rate) / mt_getrandmax(), 5);
        }
        return $json;
    }
}
