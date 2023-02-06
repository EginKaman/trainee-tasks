<?php

declare(strict_types=1);

namespace App\Services\Processing;

use App\Services\Processing\Validator\FieldValidator;
use Illuminate\Support\Facades\Date;
use League\Csv\{Reader, Writer};

class CsvProcessing implements ProcessingInterface
{
    private int $line = 1;
    private FieldValidator $fieldValidator;

    public function __construct(FieldValidator $fieldValidator)
    {
        $this->fieldValidator = $fieldValidator;
    }

    public function validate(string $path): bool|array
    {
        $csv = Reader::createFromPath($path);
        $csv->setDelimiter(',');
        if (in_array('lastUpdate', $csv->fetchOne(), true)) {
            $csv->setHeaderOffset(0);
            ++$this->line;
        }
        foreach ($csv->getRecords() as $record) {
            $record = $this->mapRecord($record);

            $this->fieldValidator->validate($record, FieldValidator::LAST_UPDATE_FIELD, $this->line);

            $this->fieldValidator->validate($record, FieldValidator::NAME_FIELD, $this->line);

            $this->fieldValidator->validate($record, FieldValidator::UNIT_FIELD, $this->line);

            $this->fieldValidator->validate($record, FieldValidator::COUNTRY_FIELD, $this->line);

            $this->fieldValidator->validate($record, FieldValidator::CURRENCY_CODE_FIELD, $this->line);

            $this->fieldValidator->validate($record, FieldValidator::RATE_FIELD, $this->line);

            $this->fieldValidator->validate($record, FieldValidator::CHANGE_FIELD, $this->line);
            ++$this->line;
        }

        return !$this->fieldValidator->hasErrors() ?: $this->fieldValidator->errors();
    }

    public function read($file): void
    {
    }

    public function process(string $path)
    {
        $csv = Reader::createFromPath($path);
        $csv->setDelimiter(',');
        if (in_array('lastUpdate', $csv->fetchOne(), true)) {
            $csv->setHeaderOffset(0);
        }
        $previousLastUpdate = null;
        $updatedRecord = [];
        foreach ($csv->getRecords() as $record) {
            $lastUpdate = Date::createFromFormat('Y-m-d', $record['lastUpdate'] ?? $record[0]);
            if ($previousLastUpdate === null) {
                $previousLastUpdate = $lastUpdate;
                $lastUpdate = Date::today();
            } elseif ($lastUpdate === $previousLastUpdate) {
                $lastUpdate = $previousLastUpdate;
            } else {
                $lastUpdate = $previousLastUpdate->subDay();
            }
            $rate = round(random_int(0, 1000000) / mt_getrandmax(), 5);
            $change = round(random_int(0, (int)$rate) / mt_getrandmax(), 5);
            $date = $lastUpdate->format('Y-m-d');
            $updatedRecord[$date][] = [
                'lastUpdate' => $date,
                'name' => $record['name'] ?? $record[1],
                'unit' => $record['unit'] ?? $record[2],
                'currencyCode' => $record['currencyCode'] ?? $record[3],
                'country' => $record['country'] ?? $record[4],
                'rate' => $rate,
                'change' => $change,
            ];
        }
//        $writer = Writer::createFromString('lastUpdate,name,unit,currencyCode,country,rate,change');
//        $writer->insertAll($updatedRecord);
        return (object)$updatedRecord;
    }

    protected function mapRecord(array $record)
    {
        return [
            'lastUpdate' => $record['lastUpdate'] ?? $record[0],
            'name' => $record['name'] ?? $record[1],
            'unit' => $record['unit'] ?? $record[2],
            'currencyCode' => $record['currencyCode'] ?? $record[3],
            'country' => $record['country'] ?? $record[4],
            'rate' => $record['rate'] ?? $record[5],
            'change' => $record['change'] ?? $record[6],
        ];
    }
}
