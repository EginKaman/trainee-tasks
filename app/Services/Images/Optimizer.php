<?php

declare(strict_types=1);

namespace App\Services\Images;

use Intervention\Image\Facades\Image as InterventionImage;

class Optimizer
{
    private Image|InterventionImage $image;

    public function init(string $file, string $method): static
    {
        /** @phpstan-ignore-next-line */
        $this->image = match (mb_strtolower($method)) {
            'native' => app(Image::class)->make($file),
            'library' => InterventionImage::make($file)
        };

        return $this;
    }

    public function getWidth(): int
    {
        return $this->image->getWidth();
    }

    public function getHeight(): int
    {
        return $this->image->getHeight();
    }

    public function resize(int $width, int $height): static
    {
        $this->image->resize($width, $height);

        return $this;
    }

    public function encode(string $format): static
    {
        $this->image->encode($format);

        return $this;
    }

    public function text(string $text): static
    {
        $this->image->text($text);

        return $this;
    }

    public function save(?string $path = null): self
    {
        $this->image->save($path);

        return $this;
    }
}
