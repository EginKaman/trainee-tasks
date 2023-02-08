<?php

declare(strict_types=1);

namespace App\Services\Processing;

use App\Services\Processing\Validator\FieldValidator;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Storage;
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
        $csv = $this->read($path);
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

    public function read(string $path): Reader
    {
        $csv = Reader::createFromPath($path);
        $csv->setDelimiter(',');
        if (in_array('lastUpdate', $csv->fetchOne(), true)) {
            $csv->setHeaderOffset(0);
            ++$this->line;
        }
        return $csv;
    }

    /**
     * @param string $path
     * @return object
     * @throws \Exception
     */
    public function process(string $path)
    {
        $csv = $this->read($path);
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
            $rate = round(random_int(0, 1000000) / random_int(2, 100), 5);
            $change = round(random_int(0, (int)$rate) / random_int(2, 100), 5);
            $date = $lastUpdate->format('Y-m-d');
            $record = [
                'lastUpdate' => $date,
                'name' => $record['name'] ?? $record[1],
                'unit' => $record['unit'] ?? $record[2],
                'currencyCode' => $record['currencyCode'] ?? $record[3],
                'country' => $record['country'] ?? $record[4],
                'rate' => $rate,
                'change' => $change,
            ];
            $updatedRecord[] = $this->mapObject($this->mapRecord($record));
        }
//        $writer = Writer::createFromString('lastUpdate,name,unit,currencyCode,country,rate,change');
//        $writer->insertAll($updatedRecord);
        return (object)$updatedRecord;
    }

    /**
     * @param array $record
     * @return array
     */
    protected function mapRecord(array $record): array
    {
        return [
            'lastUpdate' => $this->fieldValidator->prepareValue(
                (string)($record['lastUpdate'] ?? $record[0] ?? ''),
                FieldValidator::LAST_UPDATE_FIELD
            ),
            'name' => $this->fieldValidator->prepareValue(
                (string)($record['name'] ?? $record[1] ?? ''),
                FieldValidator::NAME_FIELD
            ),
            'unit' => $this->fieldValidator->prepareValue(
                (string)($record['unit'] ?? $record[2] ?? ''),
                FieldValidator::UNIT_FIELD
            ),
            'currencyCode' => $this->fieldValidator->prepareValue(
                (string)($record['currencyCode'] ?? $record[3] ?? ''),
                FieldValidator::CURRENCY_CODE_FIELD
            ),
            'country' => $this->fieldValidator->prepareValue(
                (string)($record['country'] ?? $record[4] ?? ''),
                FieldValidator::COUNTRY_FIELD
            ),
            'rate' => $this->fieldValidator->prepareValue(
                (string)($record['rate'] ?? $record[5] ?? ''),
                FieldValidator::RATE_FIELD
            ),
            'change' => $this->fieldValidator->prepareValue(
                (string)($record['change'] ?? $record[6] ?? ''),
                FieldValidator::CHANGE_FIELD
            ),
        ];
    }

    /**
     * @param array $record
     * @return object
     */
    protected function mapObject(array $record): object
    {
        $currency = new \stdClass();
        $currency->name = $record['name'];
        $currency->unit = $record['unit'];
        $currency->currencyCode = $record['currencyCode'];
        $currency->country = $record['country'];
        $currency->rate = $record['rate'];
        $currency->change = $record['change'];
        $exrate = new \stdClass();
        $exrate->lastUpdate = $record['lastUpdate'];
        $exrate->currency[] = $currency;
        return $exrate;
    }

    public function write($csv, $hash)
    {
        if (!mkdir(
                directory: $concurrentDirectory = storage_path("app/public/documents/{$hash}"),
                recursive: true
            ) && !is_dir($concurrentDirectory)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
        }

        Storage::put("public/documents/{$hash}/processing_results.json", json_encode($csv));
        Storage::put("public/documents/{$hash}/processing_results.csv", '');
        Storage::put("public/documents/{$hash}/processing_results_writer.xml", '');
        $writer = Writer::createFromPath(storage_path("app/public/documents/{$hash}/processing_results.csv"));
        $writer->insertOne(['lastUpdate', 'name', 'unit', 'currencyCode', 'country', 'rate', 'change']);
        $xw = new \XMLWriter();
        $xw->openUri(storage_path("app/public/documents/{$hash}/processing_results_writer.xml"));
        $xw->startDocument('1.0', 'UTF-8');
        $xw->startElement('currencies');
        foreach ($csv as $exrate) {
            $xw->startElement('exrate');
            $xw->startElement('lastUpdate');
            $xw->text((string)$exrate->lastUpdate);
            $xw->endElement();
            foreach ($exrate->currency as $currency) {
                $xw->startElement('name');
                $xw->text((string)$currency->name);
                $xw->endElement();
                $xw->startElement('unit');
                $xw->text((string)$currency->unit);
                $xw->endElement();
                $xw->startElement('currencyCode');
                $xw->text((string)$currency->currencyCode);
                $xw->endElement();
                $xw->startElement('country');
                $xw->text((string)$currency->country);
                $xw->endElement();
                $xw->startElement('rate');
                $xw->text((string)$currency->rate);
                $xw->endElement();
                $xw->startElement('change');
                $xw->text((string)$currency->change);
                $xw->endElement();
                $writer->insertOne([
                    'lastUpdate' => (string)$exrate->lastUpdate,
                    'name' => (string)$currency->name,
                    'unit' => (string)$currency->unit,
                    'currencyCode' => (string)$currency->currencyCode,
                    'country' => (string)$currency->country,
                    'rate' => (string)$currency->rate,
                    'change' => (string)$currency->change,
                ]);
            }
            $xw->endElement();
        }
        $xw->endElement();
        $xw->endDocument();
        $xw->flush();
        Storage::put(
            "public/documents/{$hash}/processing_results_simple.xml",
            file_get_contents(storage_path("app/public/documents/{$hash}/processing_results_writer.xml"))
        );
    }
}
