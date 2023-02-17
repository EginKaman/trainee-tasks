<?php

declare(strict_types=1);

namespace App\Actions\Image;

use App\Facades\FileHelper;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TestImage
{
    public function files(): array
    {
        $files = [
            'valid' => [],
            'invalid' => [],
        ];
        $examples = Storage::disk('public')->allFiles('examples/images');
        foreach ($examples as $item) {
            if (Str::startsWith($item, 'examples/images/valid')) {
                $size = getimagesize(Storage::disk('public')->path($item));
                $files['valid'][] = [
                    'url' => Storage::url($item),
                    'size' => FileHelper::sizeForHumans(Storage::disk('public')->size($item)),
                    'dimensions' => "{$size[0]}x{$size[1]}px",
                    'name' => Str::replace('examples/images/valid', '', $item),
                ];
            } elseif (Str::startsWith($item, 'examples/images/invalid')) {
                $size = getimagesize(Storage::disk('public')->path($item));
                $files['invalid'][] = [
                    'url' => Storage::url($item),
                    'size' => FileHelper::sizeForHumans(Storage::disk('public')->size($item)),
                    'dimensions' => "{$size[0]}x{$size[1]}px",
                    'name' => Str::replace('examples/images/invalid', '', $item),
                ];
            }
        }

        return $files;
    }
}
