<?php

declare(strict_types=1);

namespace App\Services\Processing;

use App\Exceptions\UnknownProcessingException;
use DOMDocument;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Storage;
use League\ISO3166\ISO3166;
use LibXMLError;
use SimpleXMLElement;

class XmlProcessing implements ProcessingInterface
{
    private DOMDocument $DOMDocument;
    private string $schema;
    private SimpleXMLElement $XMLElement;

    /**
     * @param DOMDocument $DOMDocument
     * @throws UnknownProcessingException
     */
    public function __construct(DOMDocument $DOMDocument)
    {
        $this->DOMDocument = $DOMDocument;
        if (!file_exists(resource_path('schemas/schema.xsd'))) {
            throw new UnknownProcessingException();
        }
        $this->schema = resource_path('schemas/schema.xsd');
    }

    /**
     * @param string $path
     * @return LibXMLError[]|bool
     */
    public function validate(string $path): array|bool
    {
        libxml_use_internal_errors(true);
        $result = true;
        $errors = [];
        $this->DOMDocument->load($path);
        if (!$this->DOMDocument->schemaValidate($this->schema)) {
            $errors = libxml_get_errors();
            $result = false;
        }
        try {
            $xml = new SimpleXMLElement(file_get_contents($path));
        } catch (\Exception $e) {
        }
        $today = Date::today();
        foreach ($xml->exrate as $exrate) {
            if (Date::createFromFormat('Y-m-d', $exrate->lastUpdate) > $today) {
                $error = new LibXMLError();
                $error->message = "Element '{$exrate->getName()}': [facet 'maxExclusive'] " .
                    "The value '{$exrate->lastUpdate}' must be smaller than '$today'.";
                $error->file = $path;
                $error->line = dom_import_simplexml($exrate->lastUpdate)->getLineNo();
                $error->code = 1836;
                $error->column = 0;
                $error->level = 2;
                $errors[] = $error;
            }
            foreach ($exrate->currency as $currency) {
                $data = (new ISO3166())->alpha3((string)$currency->country);
                if (!in_array((string)$currency->currencyCode, $data['currency'], true)) {
                    $error = new LibXMLError();
                    $error->message = "Invalid currencyCode {$currency->currencyCode} for country {$data['name']}";
                    $error->file = $path;
                    $error->line = dom_import_simplexml($currency->country)->getLineNo();
                    $error->code = 1836;
                    $error->column = 0;
                    $error->level = 2;
                    $errors[] = $error;
                }
                if ((float)$currency->rate < (float)$currency->change) {
                    $error = new LibXMLError();
                    $error->message = "Rate must be greater than change";
                    $error->file = $path;
                    $error->line = dom_import_simplexml($currency->rate)->getLineNo();
                    $error->code = 1836;
                    $error->column = 0;
                    $error->level = 2;
                    $errors[] = $error;
                }
                if ((float)$currency->change > (float)$currency->rate) {
                    $error = new LibXMLError();
                    $error->message = "Change must be smaller than rate";
                    $error->file = $path;
                    $error->line = dom_import_simplexml($currency->change)->getLineNo();
                    $error->code = 1836;
                    $error->column = 0;
                    $error->level = 2;
                    $errors[] = $error;
                }
            }
        }
        return $result ?: $errors;
    }

    public function read(string $path)
    {
    }
}
