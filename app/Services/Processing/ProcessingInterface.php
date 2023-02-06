<?php

declare(strict_types=1);

namespace App\Services\Processing;

interface ProcessingInterface
{
    public function validate(string $path): bool|array;

    /**
     * @return mixed
     */
    public function read(string $path);
    public function process(string $path);
}
