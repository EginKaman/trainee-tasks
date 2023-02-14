<?php

declare(strict_types=1);

namespace App\Services\Processing;

use App\Exceptions\UnknownProcessingException;
use App\Services\Processing\Validator\{Error, FieldValidator};
use DOMDocument;
use Exception;
use Illuminate\Support\Facades\{Date, Storage};
use League\Csv\{CannotInsertRecord, Writer};
use LibXMLError;
use SimpleXMLElement;
use stdClass;

class XmlProcessing implements ProcessingInterface
{
    private DOMDocument $DOMDocument;
    private string $schema;
    private FieldValidator $fieldValidator;
    private array $results = [];

    /**
     * @throws UnknownProcessingException
     */
    public function __construct(DOMDocument $DOMDocument, FieldValidator $fieldValidator)
    {
        $this->DOMDocument = $DOMDocument;
        if (!file_exists(resource_path('schemas/schema.xsd'))) {
            throw new UnknownProcessingException();
        }
        $this->schema = resource_path('schemas/schema.xsd');
        $this->fieldValidator = $fieldValidator;
    }

    /**
     * @param LibXMLError[] $xmlErrors
     */
    protected function xmlErrorToError(array $xmlErrors): void
    {
        foreach ($xmlErrors as $xmlError) {
            $this->fieldValidator->addError(new Error($xmlError->message, $xmlError->line));
        }
    }

    protected function getLine(SimpleXMLElement $node): int
    {
        return dom_import_simplexml($node)->getLineNo();
    }

    public function validate(string $path): void
    {
        libxml_use_internal_errors(true);
        $this->DOMDocument->load($path);
        if (!$this->DOMDocument->schemaValidate($this->schema)) {
            $this->xmlErrorToError(libxml_get_errors());
        }

        $xml = $this->read($path);
        foreach ($xml->exrate as $exrate) {
            $this->fieldValidator->validate(
                $exrate,
                FieldValidator::LAST_UPDATE_FIELD,
                $this->getLine($exrate->lastUpdate)
            );

            if (!$this->fieldValidator->unique(
                $exrate->currency,
                FieldValidator::CURRENCY_CODE_FIELD,
                $this->getLine($exrate->lastUpdate)
            )) {
                continue;
            }

            foreach ($exrate->currency as $currency) {
                $this->fieldValidator->validate(
                    $currency,
                    FieldValidator::NAME_FIELD,
                    $this->getLine($currency->name)
                );
                $this->fieldValidator->validate(
                    $currency,
                    FieldValidator::UNIT_FIELD,
                    $this->getLine($currency->unit)
                );
                $this->fieldValidator->validate(
                    $currency,
                    FieldValidator::COUNTRY_FIELD,
                    $this->getLine($currency->country)
                );
                $this->fieldValidator->validate(
                    $currency,
                    FieldValidator::CURRENCY_CODE_FIELD,
                    $this->getLine($currency->currencyCode)
                );
                $this->fieldValidator->validate(
                    $currency,
                    FieldValidator::RATE_FIELD,
                    $this->getLine($currency->rate)
                );
                $this->fieldValidator->validate(
                    $currency,
                    FieldValidator::CHANGE_FIELD,
                    $this->getLine($currency->change)
                );
            }
        }
    }

    public function isValid(): bool
    {
        return $this->fieldValidator->hasErrors();
    }

    /**
     * @throws Exception
     */
    public function read(string $path): object
    {
        return new SimpleXMLElement(file_get_contents($path));
    }

    /**
     * @throws Exception
     */
    public function process(string $path): void
    {
        $xml = $this->read($path);
        $loop = 0;
        foreach ($xml->exrate as $exrate) {
            $exrate->lastUpdate = $this->fieldValidator->prepareValue(
                (string)$exrate->lastUpdate,
                FieldValidator::LAST_UPDATE_FIELD
            );
            if ($loop === 0) {
                $exrate->lastUpdate = Date::today()->format('Y-m-d');
            } else {
                $exrate->lastUpdate = Date::createFromFormat('Y-m-d', $xml->exrate[$loop - 1]->lastUpdate)
                    ->subDay()
                    ->format('Y-m-d');
            }
            foreach ($exrate->currency as $currency) {
                $currency->name = $this->fieldValidator->prepareValue(
                    (string)$currency->name,
                    FieldValidator::NAME_FIELD
                );
                $currency->unit = $this->fieldValidator->prepareValue(
                    (string)$currency->unit,
                    FieldValidator::UNIT_FIELD
                );
                $currency->currencyCode = $this->fieldValidator->prepareValue(
                    (string)$currency->currencyCode,
                    FieldValidator::CURRENCY_CODE_FIELD
                );
                $currency->country = $this->fieldValidator->prepareValue(
                    (string)$currency->country,
                    FieldValidator::COUNTRY_FIELD
                );
                $currency->rate = round(random_int(0, 1000000) / random_int(2, 100), 5);
                $currency->change = round(random_int(0, (int)$currency->rate) / random_int(2, 100), 5);
            }
            $this->results[] = $exrate;
            ++$loop;
        }
    }

    public function write(array $data, string $hash): void
    {
        if (!mkdir(
                directory: $concurrentDirectory = storage_path("app/public/documents/{$hash}"),
                recursive: true
            ) && !is_dir($concurrentDirectory)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
        }

        Storage::put("public/documents/{$hash}/processing results writer.xml", '');
        Storage::put("public/documents/{$hash}/processing results.csv", '');

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

        Storage::put("public/documents/{$hash}/processing results.json", json_encode($data));

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
