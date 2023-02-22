<?php

declare(strict_types=1);

namespace App\Actions\ProcessingImage;

use App\Enum\ProcessingImageStatus;
use App\Jobs\Images\OptimizeJob;
use App\Models\{Image, ProcessingImage};
use Illuminate\Http\File;

class NewProcessingImage
{
    public function create(Image $image, File $file, string $path, bool $isSkipped = false): void
    {
        $imagick = new \Imagick($file->getRealPath());
        $processingImage = new ProcessingImage();

        $processingImage->name = $file->getFilename();
        $processingImage->path = $path;
        $processingImage->mimetype = $file->getMimeType();
        $processingImage->original_size = $file->getSize();
        $processingImage->original_height = $imagick->getImageHeight();
        $processingImage->original_width = $imagick->getImageWidth();
        $processingImage->image()->associate($image);

        if ($isSkipped === true || !in_array($file->getMimeType(), ['image/gif', 'image/jpeg', 'image/png'], true)) {
            $processingImage->status = ProcessingImageStatus::Skipped;
        }

        $processingImage->save();

        if ($isSkipped === false) {
            OptimizeJob::dispatch($processingImage);
        }
    }
}
