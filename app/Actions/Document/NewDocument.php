<?php

declare(strict_types=1);

namespace App\Actions\Document;

use App\Exceptions\UnknownProcessingException;
use App\Services\Processing;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class NewDocument
{
    protected string $hash;
    protected string $path;
    public function __construct(
        protected Processing $processing,

    ) {
        $this->hash = Str::random(32);
    }

    /**
     * @throws UnknownProcessingException
     */
    public function store(UploadedFile $document): void
    {
        $this->processing->setMimeType($document->getMimeType());

        $this->path = $document->store('documents');
    }

    public function validate(): void
    {
        $this->processing->validate(Storage::path($this->path));
    }

    public function isValid(): bool
    {
        return $this->processing->isValid();
    }

    public function getErrors(): array
    {
        return $this->processing->errors();
    }

    public function results(): array
    {
        $this->processing->process(Storage::path($this->path));
        $results = $this->processing->results();
        $this->processing->write($results, $this->hash);

        return $results;
    }


    public function getFiles(): array
    {
        return [
            'processing_results_simple' => Storage::disk('public')->size("documents/{$this->hash}/processing results simple.xml"),
            'processing_results_writer' => Storage::disk('public')->size("/documents/{$this->hash}/processing results writer.xml"),
            'processing_results_json' => Storage::disk('public')->size("documents/{$this->hash}/processing results.json"),
            'processing_results_csv' =>Storage::disk('public')->size("documents/{$this->hash}/processing results.csv"),
        ];
    }

    public function getUrls(): array
    {
        return [
            'processing_results_simple' => Storage::url("documents/{$this->hash}/processing results simple.xml"),
            'processing_results_writer' => Storage::url("documents/{$this->hash}/processing results writer.xml"),
            'processing_results_json' => Storage::url("documents/{$this->hash}/processing results.json"),
            'processing_results_csv' => Storage::url("documents/{$this->hash}/processing results.csv"),
        ];
    }
}
