<?php

declare(strict_types=1);

namespace App\Services\Images;

use ImagickException;

class Crop
{
    /**
     * @throws ImagickException
     */
    public function handle(string $path, int $width, int $height, string $output): void
    {
        $imagick = new \Imagick($path);
        $imagick->thumbnailImage($width, $height);
        $imagick->writeImage($output);
    }
}
