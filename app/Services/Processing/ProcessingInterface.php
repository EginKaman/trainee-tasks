<?php

declare(strict_types=1);

namespace App\Services\Processing;

use SimpleXMLElement;
use stdClass;

interface ProcessingInterface
{
    public function validate(string $path): void;

    public function isValid(): bool;

    public function errors(): array;

    public function results(): array;

    public function read(string $path): object|array;

    public function process(string $path): void;

    public function write(SimpleXMLElement|stdClass|array $data, string $hash): void;
}
