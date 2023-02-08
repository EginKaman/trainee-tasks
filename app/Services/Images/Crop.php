<?php

declare(strict_types=1);

namespace App\Services\Images;

class Crop
{
    public function handle($path, int $width, int $height, string $output)
    {
        $imagick = new \Imagick($path);
        $imagick->thumbnailImage($width, $height);
        $imagick->writeImage($output);
    }
}
