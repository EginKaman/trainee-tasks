<?php

declare(strict_types=1);

namespace App\Services\Processing;

use App\Exceptions\UnknownProcessingException;
use App\Services\Processing\Validator\{Error, FieldValidator};
use DOMDocument;
use Illuminate\Support\Facades\{Date, Storage};
use Illuminate\Support\Str;
use League\Csv\Writer;
use LibXMLError;
use SimpleXMLElement;

class XmlProcessing implements ProcessingInterface
{
    private DOMDocument $DOMDocument;
    private string $schema;
    private SimpleXMLElement $XMLElement;
    private FieldValidator $fieldValidator;

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
     * @return bool|LibXMLError[]
     */
    public function validate(string $path): array|bool
    {
        libxml_use_internal_errors(true);
        $this->DOMDocument->load($path);
        if (!$this->DOMDocument->schemaValidate($this->schema)) {
            return $this->xmlErrorToError(libxml_get_errors());
        }
        $this->read($path);

        return !$this->fieldValidator->hasErrors() ?: $this->fieldValidator->errors();
    }

    public function read(string $path): void
    {
        try {
            $xml = new SimpleXMLElement(file_get_contents($path));
        } catch (\Exception $e) {
            return;
        }
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

    /**
     * @throws \Exception
     */
    public function process(string $path)
    {
        try {
            $xml = new SimpleXMLElement(file_get_contents($path));
        } catch (\Exception $e) {
            return false;
        }
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

            ++$loop;
        }
        return $xml;
    }

    public function write(SimpleXMLElement $xml, $hash)
    {
        if (!mkdir(
                directory: $concurrentDirectory = storage_path("app/public/documents/{$hash}"),
                recursive: true
            ) && !is_dir($concurrentDirectory)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
        }
        $xml->saveXML(storage_path("app/public/documents/{$hash}/processing results simple.xml"));
        $xml->saveXML(storage_path("app/public/documents/{$hash}/processing results writer.xml"));
        Storage::put("public/documents/{$hash}/processing results.json", json_encode($xml));
        Storage::put("public/documents/{$hash}/processing results.csv", '');
        $writer = Writer::createFromPath(storage_path("app/public/documents/{$hash}/processing results.csv"));
        $writer->insertOne(['lastUpdate', 'name', 'unit', 'currencyCode', 'country', 'rate', 'change']);
        foreach ($xml->exrate as $exrate) {
            foreach($exrate->currency as $currency) {
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
        }
    }

    protected function getLine($node): int
    {
        return dom_import_simplexml($node)->getLineNo();
    }

    /**
     * @param LibXMLError[] $xmlErrors
     *
     * @return Error[]
     */
    protected function xmlErrorToError(array $xmlErrors): array
    {
        $errors = [];
        foreach ($xmlErrors as $xmlError) {
            $errors[] = new Error($xmlError->message, $xmlError->line);
        }

        return $errors;
    }
}
