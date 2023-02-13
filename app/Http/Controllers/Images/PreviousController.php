<?php

declare(strict_types=1);

namespace App\Http\Controllers\Images;

use App\Http\Controllers\Controller;
use App\Models\Image;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\{Factory, View};
use Illuminate\Http\Request;

class PreviousController extends Controller
{
    public function __invoke(Request $request, Image $image): Application|Factory|View
    {
        $image->load([
            'processingImages' => function ($query): void {
                $query->orderBy('mimetype', 'desc')->orderBy('original_width', 'desc');
            },
        ]);
        $processing = $image->processingImages->groupBy(['mimetype', 'original_width']);

        return view('images.previous', compact('image', 'processing'));
    }
}
