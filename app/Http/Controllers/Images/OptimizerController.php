<?php

declare(strict_types=1);

namespace App\Http\Controllers\Images;

use App\Actions\Image\NewImage;
use App\Actions\ProcessingImage\NewProcessingImage;
use App\Facades\FileHelper;
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
    public function index(Request $request): Application|Factory|View
    {
        $image = $request->session()->get('image');
        $viewData = [];
        if ($image !== null) {
            $image->load([
                'processingImages' => function ($query): void {
                    $query->orderBy('mimetype', 'desc')->orderBy('original_width', 'desc');
                },
            ]);
            $processing = $image->processingImages->groupBy(['mimetype', 'original_width']);
            $viewData = [
                'image' => $image,
                'processing' => $processing,
            ];
        }

        return view('images.optimizer', $viewData);
    }

    public function test(): Application|Factory|View
    {
        $valid = Storage::disk('public')->files('examples/images/valid');
        foreach ($valid as $key => $item) {
            $file = new File(storage_path('app/public/' . $item));
            $valid[$key] = [
                'path' => $item,
                'size' => FileHelper::sizeForHumans($file->getSize()),
                'name' => $file->getFilename(),
            ];
        }
        $invalid = Storage::disk('public')->files('examples/images/invalid');
        foreach ($invalid as $key => $item) {
            $file = new File(storage_path('app/public/' . $item));
            $invalid[$key] = [
                'path' => $item,
                'size' => FileHelper::sizeForHumans($file->getSize()),
                'name' => $file->getFilename(),
            ];
        }

        return view('images.test_data', compact('valid', 'invalid'));
    }

    public function previous(): Application|Factory|View
    {
        return view('images.previous', [
            'images' => Image::all(),
        ]);
    }

    public function show(Request $request, Image $image): Application|Factory|View
    {
        $image->load([
            'processingImages' => function ($query): void {
                $query->orderBy('mimetype', 'desc')->orderBy('original_width', 'desc');
            },
        ]);
        $processing = $image->processingImages->groupBy(['mimetype', 'original_width']);

        return view('images.show', compact(['image', 'processing']));
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
        $output = 'public/images/' . $fileName . '.' . $file->getExtension();
        $crop->handle($file->getRealPath(), 500, 500, Storage::path($output));

        //converted images
        $annotate->handle(Storage::path($output), $fileName);
        $images = $convert->handle(Storage::path($output), $fileName);
        foreach ($images as $ext => $img) {
            $sizes = [350, 200, 150, 100, 50];
            $isSkipped = false;
            if (in_array($ext, ['gif', 'jpg', 'png'], true)) {
                $isSkipped = true;
                $sizes = [500, 350, 200, 150, 100, 50];
            }
            $newProcessingImage->create($image, new File(Storage::path($img)), $img, $isSkipped);
            foreach ($sizes as $size) {
                $output = 'public/images/' . $fileName . '_' . $size . '.' . $ext;
                $crop->handle(Storage::path($img), $size, $size, Storage::path($output));
                $newProcessingImage->create($image, new File(Storage::path($output)), $output);
            }
        }

        return redirect()->route('optimizer')->with('image', $image);
    }
}
