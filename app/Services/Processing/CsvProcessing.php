<?php

declare(strict_types=1);

namespace App\Services\Processing;

use App\Services\Processing\Validator\FieldValidator;
use Illuminate\Support\Facades\Date;
use League\Csv\Reader;
use stdClass;

class CsvProcessing implements ProcessingInterface
{
    private int $line = 1;

    private array $results = [];

    public function __construct(
        private readonly FieldValidator $fieldValidator
    ) {
    }

    public function validate(string $path): void
    {
        $data = $this->read($path);
        foreach ($data as $record) {
            if (
                !$this->fieldValidator->unique(
                    $data[$record['lastUpdate']]['currencies'],
                    FieldValidator::CURRENCY_CODE_FIELD,
                    $this->line
                )
            ) {
                break;
            }

            $this->fieldValidator->validate($record, FieldValidator::LAST_UPDATE_FIELD, $this->line);

            $this->fieldValidator->validate($record, FieldValidator::NAME_FIELD, $this->line);

            $this->fieldValidator->validate($record, FieldValidator::UNIT_FIELD, $this->line);

            $this->fieldValidator->validate($record, FieldValidator::COUNTRY_FIELD, $this->line);

            $this->fieldValidator->validate($record, FieldValidator::CURRENCY_CODE_FIELD, $this->line);

            $this->fieldValidator->validate($record, FieldValidator::RATE_FIELD, $this->line);

            $this->fieldValidator->validate($record, FieldValidator::CHANGE_FIELD, $this->line);

            ++$this->line;
        }
    }

    public function read(string $path): array
    {
        $csv = Reader::createFromPath($path);
        $csv->setDelimiter(',');
        if (in_array('lastUpdate', $csv->fetchOne(), true)) {
            $csv->setHeaderOffset(0);
            ++$this->line;
        }

        return $this->prepareRecords($csv);
    }

    public function process(string $path): void
    {
        $previousLastUpdate = null;
        $updatedRecord = [];

        $data = $this->read($path);
        $loop = 0;

        foreach ($data as $record) {
            if ($loop === 0) {
                $record['lastUpdate'] = Date::today()->format('Y-m-d');
            } else {
                $record['lastUpdate'] = Date::createFromFormat('Y-m-d', $previousLastUpdate)
                    ->subDay()
                    ->format('Y-m-d');
            }
            $previousLastUpdate = $record['lastUpdate'];

            foreach ($record['currencies'] as $index => $currency) {
                $rate = round(random_int(0, 1000000) / random_int(2, 100), 5);
                $change = round(random_int(0, (int) $rate) / random_int(2, 100), 5);

                $currency = [
                    'lastUpdate' => $record['lastUpdate'],
                    'name' => $currency['name'],
                    'unit' => $currency['unit'],
                    'currencyCode' => $currency['currencyCode'],
                    'country' => $currency['country'],
                    'rate' => $rate,
                    'change' => $change,
                ];
                $record['currencies'][$index] = $currency;
            }

            $updatedRecord[$record['lastUpdate']] = $record;

            ++$loop;
        }

        $this->mapObject($updatedRecord);
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

    protected function mapRecord(array $record): array
    {
        return [
            'lastUpdate' => $this->fieldValidator->prepareValue(
                (string) ($record['lastUpdate'] ?? $record[0] ?? ''),
                FieldValidator::LAST_UPDATE_FIELD
            ),
            'name' => $this->fieldValidator->prepareValue(
                (string) ($record['name'] ?? $record[1] ?? ''),
                FieldValidator::NAME_FIELD
            ),
            'unit' => $this->fieldValidator->prepareValue(
                (string) ($record['unit'] ?? $record[2] ?? ''),
                FieldValidator::UNIT_FIELD
            ),
            'currencyCode' => $this->fieldValidator->prepareValue(
                (string) ($record['currencyCode'] ?? $record[3] ?? ''),
                FieldValidator::CURRENCY_CODE_FIELD
            ),
            'country' => $this->fieldValidator->prepareValue(
                (string) ($record['country'] ?? $record[4] ?? ''),
                FieldValidator::COUNTRY_FIELD
            ),
            'rate' => $this->fieldValidator->prepareValue(
                (string) ($record['rate'] ?? $record[5] ?? ''),
                FieldValidator::RATE_FIELD
            ),
            'change' => $this->fieldValidator->prepareValue(
                (string) ($record['change'] ?? $record[6] ?? ''),
                FieldValidator::CHANGE_FIELD
            ),
        ];
    }

    protected function mapObject(array $records): void
    {
        foreach ($records as $record) {
            $exrate = new stdClass();
            $exrate->lastUpdate = $record['lastUpdate'];
            foreach ($record['currencies'] as $currency) {
                $object = new stdClass();
                $object->name = $currency['name'];
                $object->unit = $currency['unit'];
                $object->currencyCode = $currency['currencyCode'];
                $object->country = $currency['country'];
                $object->rate = $currency['rate'];
                $object->change = $currency['change'];
                $exrate->currency[] = $object;
            }
            $this->results[] = $exrate;
        }
    }

    protected function prepareRecords(Reader $csv): array
    {
        $data = [];
        foreach ($csv->getRecords() as $record) {
            $record = $this->mapRecord($record);
            $data[$record['lastUpdate']]['lastUpdate'] = $record['lastUpdate'];
            $data[$record['lastUpdate']]['currencies'][] = [
                'name' => $record['name'],
                'unit' => $record['unit'],
                'currencyCode' => $record['currencyCode'],
                'country' => $record['country'],
                'rate' => $record['rate'],
                'change' => $record['change'],
            ];
        }

        return $data;
    }
}
