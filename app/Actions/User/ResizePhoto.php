<?php

declare(strict_types=1);

namespace App\Actions\User;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class ResizePhoto
{
    public function resize(string $photo, int $width, int $height, ?string $postfix = null): string
    {
        $image = Image::make(Storage::path($photo));
        $image->resize($width, $height);
        if ($postfix !== null) {
            $photo = Str::replace($image->filename, $image->filename . '_' . $postfix, $photo);
        }
        $image->save(Storage::path($photo));

        return $photo;
    }
}
