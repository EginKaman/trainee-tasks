<?php

declare(strict_types=1);

namespace App\Actions\Image;

use App\Models\Image;
use App\Services\Images\Optimizer;
use Illuminate\Http\{File, UploadedFile};
use Illuminate\Support\Facades\Storage;

class NewImage
{
    public function __construct(
        private Optimizer $optimizer,
        private PrepareImage $prepareImage,
        private ConvertImage $convertImage
    ) {
    }

    public function create(UploadedFile $uploadedFile, string $method): Image
    {
        Storage::makeDirectory('public/images/original');
        Storage::makeDirectory('public/images/annotated');

        $path = $uploadedFile->store('public/images/original');
        $file = new File(Storage::path($path));
        $filename = $file->getBasename(".{$file->getExtension()}");
        $this->optimizer = $this->optimizer->init($file->getRealPath(), $method);

        $image = new Image();
        $image->filename = $uploadedFile->getClientOriginalName();
        $image->path = $path;
        $image->size = $uploadedFile->getSize();
        $image->hash = $file->getBasename(".{$file->getExtension()}");
        $image->mimetype = $file->getMimeType();
        $image->height = $this->optimizer->getWidth();
        $image->width = $this->optimizer->getHeight();
        $image->save();

        $output = 'public/images/' . $filename . '.' . $file->getExtension();
        $this->prepareImage->prepare($method, $file->getRealPath(), $output);

        //converted images
        $this->convertImage->convert($image, Storage::path($output), $filename, $method);

        return $image;
    }
}
