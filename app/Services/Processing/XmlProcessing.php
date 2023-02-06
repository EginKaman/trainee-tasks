<?php

declare(strict_types=1);

namespace App\Services\Processing;

use App\Exceptions\UnknownProcessingException;
use App\Services\Processing\Validator\{Error, FieldValidator};
use DOMDocument;
use Illuminate\Support\Facades\{Date, Storage};
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
    public function process(string $path): SimpleXMLElement|bool
    {
        try {
            $xml = new SimpleXMLElement(file_get_contents($path));
        } catch (\Exception $e) {
            return false;
        }
        $loop = 0;
        foreach ($xml->exrate as $exrate) {
            if ($loop === 0) {
                $exrate->lastUpdate = Date::today()->format('Y-m-d');
            } else {
                $exrate->lastUpdate = Date::createFromFormat('Y-m-d', $xml->exrate[$loop - 1]->lastUpdate)
                    ->subDay()
                    ->format('Y-m-d');
            }
            foreach ($exrate->currency as $currency) {
                $currency->rate = round(random_int(0, 1000000) / random_int(2, 100), 5);
                $currency->change = round(random_int(0, (int) $currency->rate) / random_int(2, 100), 5);
            }

            ++$loop;
        }

        return $xml;
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
