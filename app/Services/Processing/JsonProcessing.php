<?php

declare(strict_types=1);

namespace App\Services\Processing;

use App\Services\Processing\Validator\{Error, FieldValidator};
use Exception;
use Illuminate\Support\Facades\Date;
use RamosHenrique\JsonSchemaValidator\JsonSchemaValidator;
use Swaggest\JsonSchema\Schema;

class JsonProcessing implements ProcessingInterface
{
    private string $schema;
    private int $line = 0;
    private FieldValidator $fieldValidator;

    public function __construct(JsonSchemaValidator $jsonSchemaValidator, FieldValidator $fieldValidator)
    {
        $this->schema = resource_path('schemas/schema.json');
        $this->fieldValidator = $fieldValidator;
    }

    public function validate(string $path): bool|array
    {
        try {
            $json = $this->read($path);
        } catch (Exception $exception) {
            return [new Error($exception->getMessage(), 1)];
        }

        foreach ($json as $exrate) {
            $this->line += 2;
            $this->fieldValidator->validate($exrate, FieldValidator::LAST_UPDATE_FIELD, $this->line);
            foreach ($exrate->currency as $currency) {
                ++$this->line;
                $this->fieldValidator->validate($currency, FieldValidator::NAME_FIELD, ++$this->line);

                $this->fieldValidator->validate($currency, FieldValidator::UNIT_FIELD, ++$this->line);

                $this->fieldValidator->validate($currency, FieldValidator::COUNTRY_FIELD, ++$this->line);

                $this->fieldValidator->validate($currency, FieldValidator::CURRENCY_CODE_FIELD, ++$this->line);

                $this->fieldValidator->validate($currency, FieldValidator::RATE_FIELD, ++$this->line);

                $this->fieldValidator->validate($currency, FieldValidator::CHANGE_FIELD, ++$this->line);
                ++$this->line;
            }
            ++$this->line;
        }

        return !$this->fieldValidator->hasErrors() ?: $this->fieldValidator->errors();
    }

    public function read($path)
    {
        return Schema::import(json_decode(file_get_contents($this->schema)))->in(json_decode(file_get_contents($path)));
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
            foreach ($exrate->currency as $currency) {
                $currency->rate = round(random_int(0, 1000000) / random_int(2, 100), 5);
                $currency->change = round(random_int(0, (int) $currency->rate) / random_int(2, 100), 5);
            }
        }

        return $json;
    }
}
