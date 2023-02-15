<?php

declare(strict_types=1);

namespace App\Services\Images;

class Convert
{
    public function handle(string $path, string $filename): array
    {
        $imagick = new \Imagick($path);
        $imagick->setFilename($filename);
        $images = [];
        $images['gif'] = $this->convertToGif($imagick);
        $images['jpg'] = $this->convertToJpeg($imagick);
        $images['webp'] = $this->convertToWebp($imagick);
        $images['png'] = $this->convertToPng($imagick);
        $images['bmp'] = $this->convertToBmp($imagick);

        $imagick->clear();

        return $images;
    }

    public function convertToJpeg(\Imagick $imagick): string
    {
        $imagick->setImageBackgroundColor('white');
        $imagick = $imagick->mergeImageLayers(\Imagick::LAYERMETHOD_FLATTEN);
        $imagick->setInterlaceScheme(\Imagick::INTERLACE_PLANE);
        $imagick->setFormat('jpg');
        $imagick->setImageFormat('jpg');
        $imagick->writeImage(storage_path("app/public/images/{$imagick->getFilename()}.jpg"));

        return "public/images/{$imagick->getFilename()}.jpg";
    }

    public function convertToWebp(\Imagick $imagick): string
    {
        $imagick->setFormat('webp');
        $imagick->setImageFormat('webp');
        $imagick->writeImage(storage_path("app/public/images/{$imagick->getFilename()}.webp"));

        return "public/images/{$imagick->getFilename()}.webp";
    }

    public function convertToPng(\Imagick $imagick): string
    {
        $imagick->setFormat('png');
        $imagick->setImageFormat('png');
        $imagick->writeImage(storage_path("app/public/images/{$imagick->getFilename()}.png"));

        return "public/images/{$imagick->getFilename()}.png";
    }
    public function convertToGif(\Imagick $imagick): string
    {
        $imagick->setFormat('gif');
        $imagick->setImageFormat('gif');
        $imagick->writeImage(storage_path("app/public/images/{$imagick->getFilename()}.gif"));

        return "public/images/{$imagick->getFilename()}.gif";
    }
    public function convertToBmp(\Imagick $imagick): string
    {
        $imagick->setFormat('bmp');
        $imagick->setImageFormat('bmp');
        $imagick->writeImage(storage_path("app/public/images/{$imagick->getFilename()}.bmp"));

        return "public/images/{$imagick->getFilename()}.bmp";
    }
}
