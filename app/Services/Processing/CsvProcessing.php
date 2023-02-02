<?php

namespace App\Services\Processing;

class CsvProcessing implements ProcessingInterface
{
    use ValidatorTrait;

    public function validate(string $path): bool|array
    {
        // TODO: Implement validate() method.
    }

    public function read($file)
    {
        // TODO: Implement read() method.
    }
}
