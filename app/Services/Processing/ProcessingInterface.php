<?php

declare(strict_types=1);

namespace App\Services\Processing;

use SimpleXMLElement;
use stdClass;

interface ProcessingInterface
{
    public function validate(string $path): bool|array;

    public function read(string $path): object;
    public function process(string $path): bool|object|array;

    public function write(SimpleXMLElement|stdClass|array $data, string $hash): void;
}
