<?php

declare(strict_types=1);

namespace App\Actions\Image;

use App\Models\Image;
use Illuminate\Http\{File, UploadedFile};
use Illuminate\Support\Facades\Storage;

class NewImage
{
    public function create(UploadedFile $uploadedFile): Image
    {
        Storage::makeDirectory('public/images/original');
        Storage::makeDirectory('public/images/annotated');
        $path = $uploadedFile->store('public/images/original');
        $image = new Image();
        $image->filename = $uploadedFile->getClientOriginalName();
        $image->path = $path;
        $image->size = $uploadedFile->getSize();
        $file = new File(Storage::path($path));
        $image->hash = $file->getBasename(".{$file->getExtension()}");
        $imagick = new \Imagick($file->getRealPath());
        $image->mimetype = $file->getMimeType();
        $image->height = $imagick->getImageWidth();
        $image->width = $imagick->getImageHeight();
        $image->save();

        return $image;
    }
}
