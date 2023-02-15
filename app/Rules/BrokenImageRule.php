<?php

declare(strict_types=1);

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class BrokenImageRule implements Rule
{
    public function passes($attribute, $value): bool
    {
        $image = getimagesize($value->getRealPath());
        if (!$image) {
            return false;
        }

        return !($image[0] <= 0 || $image[1] <= 0);
    }

    public function message(): string
    {
        return 'Your image is broken. Upload intact image';
    }
}
