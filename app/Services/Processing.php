<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\UnknownProcessingException;
use App\Services\Processing\{CsvProcessing, JsonProcessing, ProcessingInterface, XmlProcessing};

class Processing
{
    private string $mimeType;
    private ProcessingInterface $processing;

    /**
     * @throws UnknownProcessingException
     *
     * @return $this
     */
    public function setMimeType(string $mimeType): static
    {
        $this->mimeType = $mimeType;
        $this->selectProcessing();

        return $this;
    }

    public function validate(string $path): bool|array
    {
        return $this->processing->validate($path);
    }

    public function process(string $path)
    {
        return $this->processing->process($path);
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
