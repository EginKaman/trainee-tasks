<?php

namespace App\Services\Processing;

use Illuminate\Http\UploadedFile;

interface ProcessingInterface
{
    /**
     * @param UploadedFile $file
     * @param $schema
     * @return bool|array
     */
    public function validate(UploadedFile $file, $schema): bool|array;

    public function read($file);
}
