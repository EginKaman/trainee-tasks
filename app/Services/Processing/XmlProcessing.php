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
    use ValidatorTrait;

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
        $this->DOMDocument->load($path);
        if (!$this->DOMDocument->schemaValidate($this->schema)) {
            $this->errors = libxml_get_errors();
            $result = false;
        }
        try {
            $xml = new SimpleXMLElement(file_get_contents($path));
        } catch (\Exception $e) {
            return false;
        }
        foreach ($xml->exrate as $exrate) {
            $this->lastUpdateValidate((string)$exrate->lastUpdate);
            foreach ($exrate->currency as $currency) {
                $this->currencyCodeValidate((string)$currency->currencyCode, (string)$currency->country);
                $this->rateChangeValidate($currency->rate, $currency->change);
            }
        }
        return $result ?: $this->errors;
    }

    public function read(string $path)
    {
    }

    public function process(string $path)
    {
        try {
            $xml = new SimpleXMLElement(file_get_contents($path));
        } catch (\Exception $e) {
            return false;
        }
        foreach ($xml->exrate as $key => $exrate) {
            if ($key === 0) {
                $exrate->lastUpdate = Date::today()->format('Y-m-d');
            } else {
                $exrate->lastUpdate = Date::createFromFormat('Y-m-d', $xml->exrate[$key - 1]->lastUpdate)
                    ->subDay()
                    ->format('Y-m-d');
            }
            foreach ($exrate->currency as $currency) {
                $currency->rate = round(random_int(0, 1000000) / mt_getrandmax(), 5);
                $currency->change = round(random_int(0, (int)$currency->rate) / mt_getrandmax(), 5);
            }
        }
        return $xml->asXML();
    }
}
