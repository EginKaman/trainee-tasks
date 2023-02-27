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
                $files['valid'][] = $this->prepareItem($item, 'examples/images/valid');
            } elseif (Str::startsWith($item, 'examples/images/invalid')) {
                $files['invalid'][] = $this->prepareItem($item, 'examples/images/invalid');
            }
        }

        return $files;
    }

    private function prepareItem(string $item, string $replace): array
    {
        $size = getimagesize(Storage::disk('public')->path($item));

        return [
            'url' => Storage::url($item),
            'size' => FileHelper::sizeForHumans(Storage::disk('public')->size($item)),
            'dimensions' => "{$size[0]}x{$size[1]}px",
            'name' => Str::replace($replace, '', $item),
        ];
    }
}
