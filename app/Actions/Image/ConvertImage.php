<?php

declare(strict_types=1);

namespace App\Actions\Image;

use App\Actions\ProcessingImage\NewProcessingImage;
use App\Models\Image;
use App\Services\Images\{Annotate, Convert, Crop};
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

class ConvertImage
{
    public function __construct(
        private Annotate $annotate,
        private Convert $convert,
        private Crop $crop,
        private NewProcessingImage $newProcessingImage
    ) {
    }

    public function convert(Image $image, string $output, string $filename): void
    {
        $this->annotate->handle($output, $filename);
        $images = $this->convert->handle($output, $filename);
        foreach ($images as $ext => $img) {
            $sizes = [350, 200, 150, 100, 50];
            $isSkipped = false;
            if (in_array($ext, ['gif', 'jpg', 'png'], true)) {
                $isSkipped = true;
                $sizes = [500, 350, 200, 150, 100, 50];
            }
            $this->newProcessingImage->create($image, new File(Storage::path($img)), $img, $isSkipped);
            foreach ($sizes as $size) {
                $output = 'public/images/' . $filename . '_' . $size . '.' . $ext;
                $this->crop->handle(Storage::path($img), $size, $size, Storage::path($output));
                $this->newProcessingImage->create($image, new File(Storage::path($output)), $output);
            }
        }
    }
}
