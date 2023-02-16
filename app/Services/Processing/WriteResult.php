<?php

declare(strict_types=1);

namespace App\Services\Processing;

use Illuminate\Support\Facades\Storage;
use League\Csv\Writer;
use XMLWriter;

class WriteResult
{
    public function write(array $data, string $hash): void
    {
        $this->dir($hash);
        $this->put($hash);

        $writer = $this->csvWriter($hash);
        $xw = $this->xmlWriter($hash);

        $xw->startElement('currencies');

        foreach ($data as $exrate) {
            $xw->startElement('exrate');

            $xw->startElement('lastUpdate');
            $xw->text((string) $exrate->lastUpdate);
            $xw->endElement();

            foreach ($exrate->currency as $currency) {
                $xw->startElement('currency');

                $xw->startElement('name');
                $xw->text((string) $currency->name);
                $xw->endElement();

                $xw->startElement('unit');
                $xw->text((string) $currency->unit);
                $xw->endElement();

                $xw->startElement('currencyCode');
                $xw->text((string) $currency->currencyCode);
                $xw->endElement();

                $xw->startElement('country');
                $xw->text((string) $currency->country);
                $xw->endElement();

                $xw->startElement('rate');
                $xw->text((string) $currency->rate);
                $xw->endElement();

                $xw->startElement('change');
                $xw->text((string) $currency->change);
                $xw->endElement();

                $writer->insertOne([
                    'lastUpdate' => (string) $exrate->lastUpdate,
                    'name' => (string) $currency->name,
                    'unit' => (string) $currency->unit,
                    'currencyCode' => (string) $currency->currencyCode,
                    'country' => (string) $currency->country,
                    'rate' => (string) $currency->rate,
                    'change' => (string) $currency->change,
                ]);

                $xw->endElement();
            }

            $xw->endElement();
        }

        $xw->endElement();

        $xw->endDocument();

        $xw->flush();

        $this->copy($hash);
        $this->writeJson($hash, $data);
    }
    protected function put(string $hash): void
    {
        Storage::put("public/documents/{$hash}/processing results writer.xml", '');
        Storage::put("public/documents/{$hash}/processing results.csv", '');
    }

    protected function csvWriter(string $hash): Writer
    {
        $writer = Writer::createFromPath(storage_path("app/public/documents/{$hash}/processing results.csv"));
        $writer->insertOne(['lastUpdate', 'name', 'unit', 'currencyCode', 'country', 'rate', 'change']);

        return $writer;
    }

    protected function xmlWriter(string $hash): XMLWriter
    {
        $xw = new XMLWriter();
        $xw->openUri(storage_path("app/public/documents/{$hash}/processing results writer.xml"));
        $xw->startDocument('1.0', 'UTF-8');

        return $xw;
    }

    protected function copy(string $hash): void
    {
        Storage::put(
            "public/documents/{$hash}/processing results simple.xml",
            file_get_contents(storage_path("app/public/documents/{$hash}/processing results writer.xml"))
        );
    }

    protected function writeJson(string $hash, array $data): void
    {
        Storage::put("public/documents/{$hash}/processing results.json", json_encode($data));
    }

    protected function dir(string $hash): void
    {
        if (!mkdir(
            directory: $concurrentDirectory = storage_path("app/public/documents/{$hash}"),
            recursive: true
        ) && !is_dir($concurrentDirectory)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
        }
    }
}
