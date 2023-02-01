<?php

namespace App\Services\Processing;

interface ProcessingInterface
{
    /**
     * @param string $path
     * @return bool|array
     */
    public function validate(string $path): bool|array;

    /**
     * @param string $path
     * @return mixed
     */
    public function read(string $path);
}
