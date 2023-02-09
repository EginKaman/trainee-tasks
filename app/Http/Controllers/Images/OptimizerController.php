<?php

declare(strict_types=1);

namespace App\Http\Controllers\Images;

use App\Http\Controllers\Controller;
use App\Http\Requests\Images\StoreOptimizerRequest;
use App\Services\Images\{Annotate, Convert, Crop};
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\{Factory, View};
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
        $image = $request->image->store('public/images');
        $crop->handle(Storage::path($image), 500, 500, Storage::path($image));
        $annotate->handle(Storage::path($image));
        Storage::makeDirectory('public/images');
        $images = $convert->handle(Storage::path($image));
        $cropped = [];
        foreach ($images as $ext => $image) {
            foreach ([350, 200, 150, 100, 50] as $size) {
                $hash = Str::random(32);
                $output = 'public/images/' . $hash . '_' . $size . '.' . $ext;
                $crop->handle(Storage::path($image), $size, $size, Storage::path($output));
                $cropped[$ext][$size] = $output;
            }
        }

        return view('images.optimizer', compact(['images', 'cropped']));
    }
}
