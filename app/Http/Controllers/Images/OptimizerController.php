<?php

declare(strict_types=1);

namespace App\Http\Controllers\Images;

use App\Actions\Image\NewImage;
use App\Actions\ProcessingImage\NewProcessingImage;
use App\Http\Controllers\Controller;
use App\Http\Requests\Images\StoreOptimizerRequest;
use App\Models\Image;
use App\Services\Images\{Annotate, Convert, Crop};
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\{Factory, View};
use Illuminate\Http\{File, RedirectResponse, Request};
use Illuminate\Support\Facades\Storage;

class OptimizerController extends Controller
{
    public function index(): Application|Factory|View
    {
        return view('images.optimizer');
    }

    public function show(Request $request, Image $image): Application|Factory|View
    {
        $image->load([
            'processingImages' => function ($query): void {
                $query->orderBy('mimetype', 'desc')->orderBy('original_width', 'desc');
            },
        ]);
        $processing = $image->processingImages->groupBy(['mimetype', 'original_width']);

        return view('images.optimizer', compact(['image', 'processing']));
    }

    public function store(
        StoreOptimizerRequest $request,
        Annotate $annotate,
        Convert $convert,
        Crop $crop,
        NewProcessingImage $newProcessingImage
    ): RedirectResponse {
        $image = $request->image;
        $image = app(NewImage::class)->create($image);
        $file = new File(Storage::path($image->path));
        $fileName = $file->getBasename(".{$file->getExtension()}");

        //500x500 original image
        $output = 'public/images/' . $fileName . '_500.' . $file->getExtension();
        $crop->handle($file->getRealPath(), 500, 500, Storage::path($output));
        $newProcessingImage->create($image, new File(Storage::path($output)), $output);

        //converted images
        $annotate->handle(Storage::path($output), $fileName);
        $images = $convert->handle(Storage::path($output), $fileName . '_500');
        $cropped = [];
        foreach ($images as $ext => $img) {
            $newProcessingImage->create($image, new File(Storage::path($img)), $img);
            foreach ([350, 200, 150, 100, 50] as $size) {
                $output = 'public/images/' . $fileName . '_' . $size . '.' . $ext;
                $crop->handle(Storage::path($img), $size, $size, Storage::path($output));
                $newProcessingImage->create($image, new File(Storage::path($output)), $output);
                $cropped[$ext][$size] = $output;
            }
        }

        return redirect()->route('optimizer.show', $image);
    }
}
