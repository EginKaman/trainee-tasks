<?php

declare(strict_types=1);

namespace App\Actions\Image;

use App\Models\Image;

class LoadImage
{
    public function load(Image $image): array
    {
        $image->load([
            'processingImages' => function ($query): void {
                $query->orderBy('mimetype', 'desc')->orderBy('original_width', 'desc');
            },
        ]);
        $processing = $image->processingImages->groupBy(['mimetype', 'original_width']);

        return [
            'image' => $image,
            'processing' => $processing,
        ];
    }
}
