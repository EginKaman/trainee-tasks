<?php

declare(strict_types=1);

namespace App\Services\Images;

use Illuminate\Support\Str;

class Convert
{
    public function handle(string $path): array
    {
        $imagick = new \Imagick($path);
        $imagick->setFilename(Str::random(32));
        $images = [];
        $images['gif'] = $this->convertToGif($imagick);
        $images['jpeg'] = $this->convertToJpeg($imagick);
        $images['webp'] = $this->convertToWebp($imagick);
        $images['png'] = $this->convertToPng($imagick);
        $images['bmp'] = $this->convertToBmp($imagick);

        return $images;
    }

    public function convertToJpeg(\Imagick $imagick): string
    {
        $imagick->setInterlaceScheme(\Imagick::INTERLACE_PLANE);
        $imagick->setFormat('jpg');
        $imagick->writeImage(storage_path("app/public/images/{$imagick->getFilename()}.jpg"));

        return "public/images/{$imagick->getFilename()}.jpg";
    }

    public function convertToWebp(\Imagick $imagick): string
    {
        $imagick->setImageFormat('webp');
        $imagick->writeImage(storage_path("app/public/images/{$imagick->getFilename()}.webp"));

        return "public/images/{$imagick->getFilename()}.webp";
    }

    public function convertToPng(\Imagick $imagick): string
    {
        $imagick->setImageFormat('png');
        $imagick->writeImage(storage_path("app/public/images/{$imagick->getFilename()}.png"));

        return "public/images/{$imagick->getFilename()}.png";
    }
    public function convertToGif(\Imagick $imagick): string
    {
        $imagick->setImageFormat('gif');
        $imagick->writeImage(storage_path("app/public/images/{$imagick->getFilename()}.gif"));

        return "public/images/{$imagick->getFilename()}.gif";
    }
    public function convertToBmp(\Imagick $imagick): string
    {
        $imagick->setImageFormat('bmp');
        $imagick->writeImage(storage_path("app/public/images/{$imagick->getFilename()}.bmp"));

        return "public/images/{$imagick->getFilename()}.bmp";
    }
}
