<?php

declare(strict_types=1);

namespace App\Services\Images;

use ImagickException;

class Crop
{
    /**
     * @param string $path
     * @param int $width
     * @param int $height
     * @param string $output
     * @return void
     * @throws ImagickException
     */
    public function handle(string $path, int $width, int $height, string $output): void
    {
        $imagick = new \Imagick($path);
        $imagick->thumbnailImage($width, $height);
        $imagick->writeImage($output);
    }
}
