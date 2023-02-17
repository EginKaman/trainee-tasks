<?php

declare(strict_types=1);

namespace App\Actions\Image;

use App\Services\Images\{Crop, Optimizer};
use Illuminate\Support\Facades\Storage;

class PrepareImage
{
    public function __construct(
        private Optimizer $optimizer
    ) {
    }

    public function prepare(string $method, string $path, string $output): void
    {
        $image = $this->optimizer->init($path, $method);
        $image->resize(500, 500);
        $image->save(Storage::path($output));
    }
}
