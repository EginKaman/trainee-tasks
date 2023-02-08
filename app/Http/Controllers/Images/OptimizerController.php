<?php

declare(strict_types=1);

namespace App\Http\Controllers\Images;

use App\Http\Controllers\Controller;
use App\Http\Requests\Images\StoreOptimizerRequest;
use App\Services\Images\Annotate;
use App\Services\Images\Convert;
use App\Services\Images\Crop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class OptimizerController extends Controller
{
    public function index()
    {
        return view('images.optimizer');
    }

    public function store(
        StoreOptimizerRequest $request,
        Annotate $annotate,
        Convert $convert,
        Crop $crop
    ): \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View {
        $image = $request->image->store('public/images');
        $crop->handle(Storage::path($image), 500, 500, Storage::path($image));
        $annotate->handle(Storage::path($image));
        Storage::makeDirectory('public/images');
        $images = $convert->handle(Storage::path($image));
        $cropped = [];
        foreach ($images as $ext => $image) {
            foreach ([50, 100, 150, 200, 350] as $size) {
                $hash = Str::random(32);
                $output = 'public/images/' . $hash . '_' . $size . '.' . $ext;
                $crop->handle(Storage::path($image), $size, $size, Storage::path($output));
                $cropped[$ext][$size] = $output;
            }
        }

        return view(
            'images.optimizer',
            compact([
                'images',
                'cropped'
            ])
        );
    }
}
