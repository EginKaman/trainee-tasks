<?php

declare(strict_types=1);

namespace App\Services\Images;

use App\Exceptions\NotSupportedException;
use Imagick;
use ImagickException;

class Image
{
    public function __construct(
        private Imagick $imagick,
        protected Crop $crop,
        protected Convert $convert,
        protected Annotate $annotate
    ) {
    }

    /**
     * @throws ImagickException
     */
    public function readImage(string $file): static
    {
        $this->imagick->readImage($file);

        return $this;
    }

    public function make(string $file): static
    {
        $this->readImage($file);

        $this->imagick = $this->removeAnimation($this->imagick);

        $this->imagick->setImageOrientation(\Imagick::ORIENTATION_UNDEFINED);

        return $this;
    }

    public function getWidth(): int
    {
        return $this->imagick->getImageWidth();
    }

    public function getHeight(): int
    {
        return $this->imagick->getImageHeight();
    }

    public function valid(): bool
    {
        return $this->imagick->valid();
    }

    public function resize(int $width, int $height): static
    {
        $this->imagick = $this->crop->handle($this->imagick, $width, $height);

        return $this;
    }

    public function encode(string $format): static
    {
        $this->imagick = match (mb_strtolower($format)) {
            'gif' => $this->convert->convertToGif($this->imagick),
            'png' => $this->convert->convertToPng($this->imagick),
            'jpg' => $this->convert->convertToJpeg($this->imagick),
            'bmp' => $this->convert->convertToBmp($this->imagick),
            'webp' => $this->convert->convertToWebp($this->imagick),
            default => throw new NotSupportedException("Encoding format ({$format}) is not supported."),
        };

        return $this;
    }

    public function text(string $text): static
    {
        $this->imagick = $this->annotate->handle($this->imagick, $text);

        return $this;
    }

    public function save(?string $path = null): static
    {
        $this->imagick->writeImage($path);

        return $this;
    }

    protected function removeAnimation(Imagick $object): Imagick
    {
        $imagick = new \Imagick();

        foreach ($object as $frame) {
            $imagick->addImage($frame->getImage());

            break;
        }

        $object->destroy();

        return $imagick;
    }
}
