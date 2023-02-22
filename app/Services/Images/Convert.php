<?php

declare(strict_types=1);

namespace App\Services\Images;

use Imagick;

class Convert
{
    public function convertToJpeg(Imagick $imagick): Imagick
    {
        $imagick->setImageBackgroundColor('white');
        $imagick = $imagick->mergeImageLayers(Imagick::LAYERMETHOD_FLATTEN);
        $imagick->setInterlaceScheme(Imagick::INTERLACE_PLANE);
        $imagick->setFormat('jpg');
        $imagick->setImageFormat('jpg');

        return $imagick;
    }

    public function convertToWebp(Imagick $imagick): Imagick
    {
        $imagick->setFormat('webp');
        $imagick->setImageFormat('webp');

        return $imagick;
    }

    public function convertToPng(Imagick $imagick): Imagick
    {
        $imagick->setFormat('png');
        $imagick->setImageFormat('png');

        return $imagick;
    }

    public function convertToGif(Imagick $imagick): Imagick
    {
        $imagick->setFormat('gif');
        $imagick->setImageFormat('gif');

        return $imagick;
    }

    public function convertToBmp(Imagick $imagick): Imagick
    {
        $imagick->setFormat('bmp');
        $imagick->setImageFormat('bmp');

        return $imagick;
    }
}
