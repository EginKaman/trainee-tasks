<?php

declare(strict_types=1);

namespace App\Actions\Image;

use App\Actions\ProcessingImage\NewProcessingImage;
use App\Models\Image;
use App\Services\Images\{Annotate, Convert, Crop, Optimizer};
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

class ConvertImage
{
    public function __construct(
        private Optimizer $optimizer,
        private NewProcessingImage $newProcessingImage
    ) {
    }

    public function convert(Image $image, string $output, string $filename, string $method): void
    {
        foreach (['bmp', 'gif', 'png', 'jpg', 'webp'] as $format) {
            $formatted = $this->optimizer->init($output, $method);
            $formatted = $formatted->encode($format);

            $path = "public/images/{$filename}.{$format}";
            $fullpath = Storage::path($path);

            $formatted->save($fullpath);

            $isSkipped = false;
            $sizes = config('image.sizes.defaults');

            if (in_array($format, ['gif', 'jpg', 'png'], true)) {
                $isSkipped = true;
                $sizes = config('image.sizes.optimized');
            }

            $this->newProcessingImage->create($image, new File($fullpath), $path, $isSkipped);

            foreach ($sizes as $size) {
                $path = 'public/images/' . $filename . '_' . $size . '.' . $format;
                $fullpath = Storage::path($path);

                $formatted->resize($size, $size);
                $formatted->save($fullpath);

                $this->newProcessingImage->create($image, new File($fullpath), $path);
            }
        }
    }
}
