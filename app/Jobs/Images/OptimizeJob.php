<?php

declare(strict_types=1);

namespace App\Jobs\Images;

use App\Models\Image;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\{InteractsWithQueue, SerializesModels};

class OptimizeJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        public Image $image
    ) {
    }

    public function handle(): void
    {
        $response = \KrakenIO::upload([
            'file' => $this->image,
            'lossy' => true,
            'quality' => 80,
            'callback_url' => route('callback'),
            'wait' => true,
            'resize' => [
                [
                    'id' => '500',
                    'strategy' => 'auto',
                    'width' => 500,
                    'height' => 500,
                ],
                [
                    'id' => '350',
                    'strategy' => 'auto',
                    'width' => 350,
                    'height' => 350,
                ],
                [
                    'id' => '200',
                    'strategy' => 'auto',
                    'width' => 200,
                    'height' => 200,
                ],
                [
                    'id' => '150',
                    'strategy' => 'auto',
                    'width' => 150,
                    'height' => 150,
                ],
                [
                    'id' => '100',
                    'strategy' => 'auto',
                    'width' => 100,
                    'height' => 100,
                ],
                [
                    'id' => '50',
                    'strategy' => 'auto',
                    'width' => 50,
                    'height' => 50,
                ],
            ],
        ]);
    }
}
