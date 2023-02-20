<?php

declare(strict_types=1);

namespace App\Actions\User;

use App\Actions\ProcessingImage\NewProcessingImage;
use Illuminate\Http\File;
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

        $model = new \App\Models\Image();
        $model->filename = $image->basename;
        $model->path = $photo;
        $model->size = $image->filesize();
        $model->hash = $image->filename;
        $model->mimetype = $image->mime();
        $model->height = $image->getWidth();
        $model->width = $image->getHeight();
        $model->save();

        app(NewProcessingImage::class)->create($model, new File(Storage::path($photo)), $photo);

        return $photo;
    }
}
