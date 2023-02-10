<?php

declare(strict_types=1);

namespace App\Services\Processing;

use App\Services\Processing\Validator\FieldValidator;
use Exception;
use Illuminate\Support\Facades\{Date, Storage};
use League\Csv\{Reader, Writer};
use SimpleXMLElement;
use stdClass;

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
        $data = $this->prepareRecords($csv);
        foreach ($csv->getRecords() as $record) {
            $record = $this->mapRecord($record);

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
     * @throws Exception
     */
    public function process(string $path): array
    {
        $csv = $this->read($path);
        $previousLastUpdate = null;
        $updatedRecord = [];
        $records = $csv->getRecords();
        $data = $this->prepareRecords($csv);
        $loop = 0;
        foreach ($data as $key => $record) {

            if ($loop === 0) {
                $record['lastUpdate'] = Date::today()->format('Y-m-d');
            } else {
                $record['lastUpdate'] = Date::createFromFormat('Y-m-d', $previousLastUpdate)
                    ->subDay()
                    ->format('Y-m-d');
            }
            $previousLastUpdate = $key;
            foreach ($record['currencies'] as $index => $currency) {
                $rate = round(random_int(0, 1000000) / random_int(2, 100), 5);
                $change = round(random_int(0, (int)$rate) / random_int(2, 100), 5);
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
        return $this->mapObject($updatedRecord);
    }

    public function write(array|SimpleXMLElement|stdClass $data, string $hash): void
    {
        if (!mkdir(
                directory: $concurrentDirectory = storage_path("app/public/documents/{$hash}"),
                recursive: true
            ) && !is_dir($concurrentDirectory)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
        }

        Storage::put("public/documents/{$hash}/processing results.json", json_encode($data));
        Storage::put("public/documents/{$hash}/processing results.csv", '');
        Storage::put("public/documents/{$hash}/processing results_writer.xml", '');
        $writer = Writer::createFromPath(storage_path("app/public/documents/{$hash}/processing results.csv"));
        $writer->insertOne(['lastUpdate', 'name', 'unit', 'currencyCode', 'country', 'rate', 'change']);
        $xw = new \XMLWriter();
        $xw->openUri(storage_path("app/public/documents/{$hash}/processing results writer.xml"));
        $xw->startDocument('1.0', 'UTF-8');
        $xw->startElement('currencies');
        foreach ($data as $exrate) {
            $xw->startElement('exrate');
            $xw->startElement('lastUpdate');
            $xw->text((string)$exrate->lastUpdate);
            $xw->endElement();

            foreach ($exrate->currency as $currency) {
                $xw->startElement('currency');
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
                $xw->endElement();
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

    protected function mapObject($records): array
    {
        $data = [];
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
            $data[] = $exrate;
        }

        return $data;
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
