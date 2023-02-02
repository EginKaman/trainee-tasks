<?php

namespace App\Services\Processing;

use Exception;
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
     * @throws JsonSchemaValidatorException
     */
    public function validate(string $path): bool|array
    {
        try {
            $schema = Schema::import(json_decode(file_get_contents($this->schema)));
            $json = $schema->in(json_decode(file_get_contents($path)));
        } catch (Exception | InvalidValue $exception) {
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
}
