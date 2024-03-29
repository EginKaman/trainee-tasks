<?php

declare(strict_types=1);

namespace App\Services\Processing;

use App\Services\Processing\Validator\{Error, FieldValidator};
use Exception;
use Illuminate\Support\Facades\{Date, Storage};
use Swaggest\JsonSchema\Schema;

class JsonProcessing implements ProcessingInterface
{
    private string $schema;
    private int $line = 1;
    private FieldValidator $fieldValidator;
    private array $results;

    public function __construct(FieldValidator $fieldValidator)
    {
        $this->schema = resource_path('schemas/schema.json');
        $this->fieldValidator = $fieldValidator;
    }

    public function validate(string $path): void
    {
        try {
            $json = $this->read($path);
        } catch (Exception $exception) {
            $this->fieldValidator->addError(new Error($exception->getMessage(), 1));

            return;
        }

        foreach ($json as $key => $exrate) {
            $this->line += 2;
            $this->fieldValidator->validate($exrate, FieldValidator::LAST_UPDATE_FIELD, ++$this->line);

            if (
                !$this->fieldValidator->unique($exrate->currency, FieldValidator::CURRENCY_CODE_FIELD, $this->line)
            ) {
                continue;
            }

            ++$this->line;
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
    }

    public function read(string $path): object|array
    {
        return Schema::import(json_decode(file_get_contents($this->schema)))->in(json_decode(file_get_contents($path)));
    }

    public function process(string $path): void
    {
        $json = $this->read($path);
        foreach ($json as $key => $exrate) {
            $exrate->lastUpdate = $this->fieldValidator->prepareValue(
                $exrate->lastUpdate,
                FieldValidator::LAST_UPDATE_FIELD
            );

            if ($key === 0) {
                $exrate->lastUpdate = Date::today()->format('Y-m-d');
            } else {
                $exrate->lastUpdate = Date::createFromFormat('Y-m-d', $json[$key - 1]->lastUpdate)
                    ->subDay()
                    ->format('Y-m-d');
            }

            foreach ($exrate->currency as $currency) {
                $currency->name = $this->fieldValidator->prepareValue(
                    (string) $currency->name,
                    FieldValidator::NAME_FIELD
                );
                $currency->unit = $this->fieldValidator->prepareValue(
                    (string) $currency->unit,
                    FieldValidator::UNIT_FIELD
                );
                $currency->currencyCode = $this->fieldValidator->prepareValue(
                    (string) $currency->currencyCode,
                    FieldValidator::CURRENCY_CODE_FIELD
                );
                $currency->country = $this->fieldValidator->prepareValue(
                    (string) $currency->country,
                    FieldValidator::COUNTRY_FIELD
                );

                $currency->rate = round(random_int(0, 1000000) / random_int(2, 100), 5);
                $currency->change = round(random_int(0, (int) $currency->rate) / random_int(2, 100), 5);
            }
        }

        $this->results = $json;
    }

    public function isValid(): bool
    {
        return $this->fieldValidator->hasErrors();
    }

    public function errors(): array
    {
        return $this->fieldValidator->errors();
    }

    public function results(): array
    {
        return $this->results;
    }
}
