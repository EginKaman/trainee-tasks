<?php

declare(strict_types=1);

namespace App\Jobs\Images;

use App\Enum\ProcessingImageStatus;
use App\Models\{Image, ProcessingImage};
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\{InteractsWithQueue, SerializesModels};
use Illuminate\Support\Facades\Storage;

class OptimizeJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        public ProcessingImage $processingImage
    ) {
    }

    public function handle(): void
    {
        $response = \KrakenIO::upload([
            'file' => Storage::path($this->processingImage->path),
            'lossy' => true,
            'wait' => true,
            'quality' => 80,
        ]);

        if ($response['success'] === true) {
            Storage::put($this->processingImage->path, file_get_contents($response['kraked_url']));
            $this->processingImage->kraked_size = $response['kraked_size'];
            $this->processingImage->kraked_width = $response['kraked_width'];
            $this->processingImage->kraked_height = $response['kraked_height'];
            $this->processingImage->saved_bytes = $response['saved_bytes'];
            $this->processingImage->status = ProcessingImageStatus::Success;
            $this->processingImage->save();
        }
    }
}
