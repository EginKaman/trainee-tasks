<?php

declare(strict_types=1);

namespace App\Services\Processing;

use App\Services\Processing\Validator\{Error, FieldValidator};
use Exception;
use Illuminate\Support\Facades\{Date, Storage};
use League\Csv\Writer;
use Swaggest\JsonSchema\Schema;

class JsonProcessing implements ProcessingInterface
{
    private string $schema;
    private int $line = 1;
    private FieldValidator $fieldValidator;

    public function __construct(FieldValidator $fieldValidator)
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

        return !$this->fieldValidator->hasErrors() ?: $this->fieldValidator->errors();
    }

    public function read(string $path): object|array
    {
        return Schema::import(json_decode(file_get_contents($this->schema)))->in(json_decode(file_get_contents($path)));
    }

    public function process(string $path): object|array
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

        return $json;
    }

    public function write(object|array $json, string $hash): void
    {
        if (
            !mkdir(
                directory: $concurrentDirectory = storage_path("app/public/documents/{$hash}"),
                recursive: true
            ) && !is_dir($concurrentDirectory)
        ) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
        }

        Storage::put("public/documents/{$hash}/processing results.json", json_encode($json));
        Storage::put("public/documents/{$hash}/processing results.csv", '');
        Storage::put("public/documents/{$hash}/processing results writer.xml", '');
        $writer = Writer::createFromPath(storage_path("app/public/documents/{$hash}/processing results.csv"));
        $writer->insertOne(['lastUpdate', 'name', 'unit', 'currencyCode', 'country', 'rate', 'change']);
        $xw = new \XMLWriter();
        $xw->openUri(storage_path("app/public/documents/{$hash}/processing results writer.xml"));
        $xw->startDocument('1.0', 'UTF-8');
        $xw->startElement('currencies');
        foreach ($json as $exrate) {
            $xw->startElement('exrate');
            $xw->startElement('lastUpdate');
            $xw->text((string) $exrate->lastUpdate);
            $xw->endElement();
            foreach ($exrate->currency as $currency) {
                $xw->startElement('name');
                $xw->text((string) $currency->name);
                $xw->endElement();
                $xw->startElement('unit');
                $xw->text((string) $currency->unit);
                $xw->endElement();
                $xw->startElement('currencyCode');
                $xw->text((string) $currency->currencyCode);
                $xw->endElement();
                $xw->startElement('country');
                $xw->text((string) $currency->country);
                $xw->endElement();
                $xw->startElement('rate');
                $xw->text((string) $currency->rate);
                $xw->endElement();
                $xw->startElement('change');
                $xw->text((string) $currency->change);
                $xw->endElement();
                $writer->insertOne([
                    'lastUpdate' => (string) $exrate->lastUpdate,
                    'name' => (string) $currency->name,
                    'unit' => (string) $currency->unit,
                    'currencyCode' => (string) $currency->currencyCode,
                    'country' => (string) $currency->country,
                    'rate' => (string) $currency->rate,
                    'change' => (string) $currency->change,
                ]);
            }
            $xw->endElement();
        }
        $xw->endElement();
        $xw->endDocument();
        $xw->flush();
        Storage::put(
            "public/documents/{$hash}/processing results simple.xml",
            file_get_contents(storage_path("app/public/documents/{$hash}/processing results writer.xml"))
        );
    }
}
