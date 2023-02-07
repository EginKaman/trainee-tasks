<?php

declare(strict_types=1);

namespace App\Services\Images;

use ImagickPixel;

class Anotate
{
    public function handle($file)
    {
        $image = new \Imagick($file);
        $draw = new \ImagickDraw();
        $draw->setFillColor(new ImagickPixel('red'));
        $draw->setFont('Courier');
        $draw->setFillOpacity(0.5);
        $draw->setFontSize('30');
        for ($i = 1; $i <= 20; $i++) {
            $image->annotateImage($draw, 10, $i * 50, -45, 'Copy ' . now()->format('Y-m-d H:i:s'));
            $image->annotateImage($draw, 320, $i * 50, -45, 'Copy ' . now()->format('Y-m-d H:i:s'));
        }
        $image->setImageFormat('png');
        $image->writeImage(storage_path('app/images/') . \Str::random() . '.png');
    }
}
