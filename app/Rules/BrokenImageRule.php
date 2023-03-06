<?php

declare(strict_types=1);

namespace App\Rules;

use App\Services\Images\Image;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\UploadedFile;

class BrokenImageRule implements Rule
{
    public function passes($attribute, $value): bool
    {
        if (!$value instanceof UploadedFile) {
            return false;
        }

        try {
            $image = app(Image::class)->readImage($value->getRealPath());
        } catch (\ImagickException $exception) {
            return false;
        }

        return !($image->getHeight() <= 0 || $image->getWidth() <= 0);
    }

    public function message(): string
    {
        return 'Your image is broken. Upload intact image';
    }
}
