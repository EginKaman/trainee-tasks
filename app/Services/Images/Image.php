<?php

declare(strict_types=1);

namespace App\Services\Images;

use Imagick;
use ImagickException;

class Image
{
    public function __construct(
        public Imagick $imagick
    ) {
    }

    /**
     * @throws ImagickException
     */
    public function readImage(string $path): static
    {
        $this->imagick->readImage($path);

        return $this;
    }

    public function getImageWidth(): int
    {
        return $this->imagick->getImageWidth();
    }

    public function getImageHeight(): int
    {
        return $this->imagick->getImageHeight();
    }

    public function valid(): bool
    {
        return $this->imagick->valid();
    }
}
