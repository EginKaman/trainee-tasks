<?php

declare(strict_types=1);

namespace App\Actions\Image;

use App\Services\Images\Crop;
use Illuminate\Support\Facades\Storage;

class PrepareImage
{
    public function __construct(
        private Crop $crop
    ) {
    }

    public function prepare(string $path, string $filename, string $extension): string
    {
        $output = 'public/images/' . $filename . '.' . $extension;
        $this->crop->handle($path, 500, 500, Storage::path($output));

        return $output;
    }
}
