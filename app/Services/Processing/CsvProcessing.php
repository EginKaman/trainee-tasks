<?php

namespace App\Services\Processing;

use Illuminate\Http\File;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Storage;
use League\Csv\Reader;
use League\Csv\Writer;

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

    public function process(string $path)
    {
        $csv = Reader::createFromPath($path);
        $csv->setDelimiter(',');
        if (in_array(['lastUpdate', 'name', 'currencyCode'], $csv->fetchOne(), true)) {
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
            $updatedRecord[] = [
                'lastUpdate' => $lastUpdate->format('Y-m-d'),
                'name' => $record['name'] ?? $record[1],
                'unit' => $record['unit'] ?? $record[2],
                'currencyCode' => $record['currencyCode'] ?? $record[3],
                'country' => $record['country'] ?? $record[4],
                'rate' => $rate,
                'change' => $change
            ];
        }
        $writer = Writer::createFromString('lastUpdate,name,unit,currencyCode,country,rate,change');
        $writer->insertAll($updatedRecord);
        $file = new File($path);
        $file->
        Storage::copy($path, );
        return $updatedRecord;
    }
}
