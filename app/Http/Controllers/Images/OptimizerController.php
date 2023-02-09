<?php

declare(strict_types=1);

namespace App\Http\Controllers\Images;

use App\Actions\Image\NewImage;
use App\Http\Controllers\Controller;
use App\Http\Requests\Images\StoreOptimizerRequest;
use App\Services\Images\{Annotate, Convert, Crop};
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\{Factory, View};
use Illuminate\Support\Facades\Storage;

class OptimizerController extends Controller
{
    public function index(): Application|Factory|View
    {
        return view('images.optimizer');
    }

    public function store(
        StoreOptimizerRequest $request,
        Annotate $annotate,
        Convert $convert,
        Crop $crop
    ): Application|Factory|View {
        $image = $request->image;
        $file = app(NewImage::class)->create($image);
        $fileName = $file->getBasename(".{$file->getExtension()}");
        $output = 'public/images/' . $fileName . '_500.' . $file->getExtension();
        $crop->handle($file->getRealPath(), 500, 500, Storage::path($output));
        $annotate->handle(Storage::path($output), $fileName);
        $images = $convert->handle(Storage::path($output), $fileName . '_500');
        $cropped = [];
        foreach ($images as $ext => $image) {
            foreach ([350, 200, 150, 100, 50] as $size) {
                $output = 'public/images/' . $fileName . '_' . $size . '.' . $ext;
                $crop->handle(Storage::path($image), $size, $size, Storage::path($output));
                $cropped[$ext][$size] = $output;
            }
        }

        return view('images.optimizer', compact(['images', 'cropped']));
    }
}
