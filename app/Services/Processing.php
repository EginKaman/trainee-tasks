<?php

namespace App\Services;

use App\Exceptions\UnknownProcessingException;
use App\Services\Processing\CsvProcessing;
use App\Services\Processing\JsonProcessing;
use App\Services\Processing\ProcessingInterface;
use App\Services\Processing\XmlProcessing;

class Processing
{
    private string $mimeType;
    private ProcessingInterface $processing;

    /**
     * @param string $mimeType
     * @return $this
     * @throws UnknownProcessingException
     */
    public function setMimeType(string $mimeType): static
    {
        $this->mimeType = $mimeType;
        $this->selectProcessing();
        return $this;
    }

    /**
     * @param string $path
     * @return array|bool
     */
    public function validate(string $path): bool|array
    {
        return $this->processing->validate($path);
    }

    /**
     * @throws UnknownProcessingException
     */
    protected function selectProcessing(): void
    {
        $this->processing = match ($this->mimeType) {
            'text/xml', 'application/xml' => app(XmlProcessing::class),
            'text/json', 'application/json' => app(JsonProcessing::class),
            'text/csv' => new CsvProcessing(),
            default => throw new UnknownProcessingException(),
        };
    }

    public function process(string $path)
    {
        return $this->processing->process($path);
    }
}
