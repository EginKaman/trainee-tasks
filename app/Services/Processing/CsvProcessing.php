<?php

namespace App\Services\Processing;

use League\Csv\Reader;

class CsvProcessing implements ProcessingInterface
{
    use ValidatorTrait;

    public function validate(string $path): bool|array
    {
        $csv = Reader::createFromPath($path);
        $csv->setDelimiter(',');
        if (in_array(['lastUpdate', 'name', 'currencyCode'], $csv->fetchOne(), true)) {
            $csv->setHeaderOffset(0);
        }
        foreach ($csv->getRecords() as $record) {
            $this->lastUpdateValidate($record['lastUpdate'] ?? $record[0]);
            $this->nameValidate($record['name'] ?? $record[1]);
            $this->unitValidate($record['unit'] ?? $record[2]);
            $this->currencyCodeValidate($record['currencyCode'] ?? $record[3], $record['country'] ?? $record[4]);
            $this->countryValidate($record['country'] ?? $record[4]);
            $this->rateChangeValidate($record['rate'] ?? $record[5], $record['change'] ?? $record[6]);
        }
        return empty($this->errors) ? true : $this->errors;
    }

    public function read($file)
    {
        // TODO: Implement read() method.
    }
}
