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
                (string) $exrate->lastUpdate,
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

            $this->results[] = $exrate;

            ++$loop;
        }
    }

    public function errors(): array
    {
        return $this->fieldValidator->errors();
    }

    public function results(): array
    {
        return $this->results;
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
}
