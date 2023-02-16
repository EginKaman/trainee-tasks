<?php

declare(strict_types=1);

namespace App\Rules;

use App\Services\Images\Image;
use Illuminate\Contracts\Validation\Rule;

class BrokenImageRule implements Rule
{
    public function __construct(
        private readonly Image $image
    ) {
    }

    public function passes($attribute, $value): bool
    {
        try {
            $image = $this->image->readImage($value->getRealPath());
        } catch (\ImagickException $exception) {
            return false;
        }

        return !($image->getImageHeight() <= 0 || $image->getImageWidth() <= 0);
    }

    public function message(): string
    {
        return 'Your image is broken. Upload intact image';
    }
}
