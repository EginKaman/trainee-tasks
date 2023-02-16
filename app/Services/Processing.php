<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\UnknownProcessingException;
use App\Services\Processing\{CsvProcessing, JsonProcessing, ProcessingInterface, WriteResult, XmlProcessing};

class Processing implements ProcessingInterface
{
    private string $mimeType;
    private ProcessingInterface $processing;

    public function __construct(
        private readonly WriteResult $writeResult
    ) {
    }

    /**
     * @throws UnknownProcessingException
     */
    public function setMimeType(string $mimeType): static
    {
        $this->mimeType = $mimeType;
        $this->selectProcessing();

        return $this;
    }

    public function validate(string $path): void
    {
        $this->processing->validate($path);
    }

    public function isValid(): bool
    {
        return $this->processing->isValid();
    }

    public function errors(): array
    {
        return $this->processing->errors();
    }

    public function read(string $path): object|array
    {
        return $this->processing->read($path);
    }

    public function results(): array
    {
        return $this->processing->results();
    }

    public function process(string $path): void
    {
        $this->processing->process($path);
    }

    public function write(object|array $data, string $hash): void
    {
        $this->writeResult->write($data, $hash);
    }

    /**
     * @throws UnknownProcessingException
     */
    protected function selectProcessing(): void
    {
        $this->processing = match ($this->mimeType) {
            'text/xml', 'application/xml' => app(XmlProcessing::class),
            'text/json', 'application/json' => app(JsonProcessing::class),
            'text/csv' => app(CsvProcessing::class),
            default => throw new UnknownProcessingException(),
        };
    }
}
