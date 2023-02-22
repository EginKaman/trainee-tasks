<?php

declare(strict_types=1);

namespace App\Http\Controllers\Images;

use App\Actions\Image\{NewImage, TestImage};
use App\Http\Controllers\Controller;
use App\Http\Requests\Images\StoreOptimizerRequest;
use App\Models\Image;
use Illuminate\Contracts\View\View;
use Illuminate\Http\{RedirectResponse, Request};

class OptimizerController extends Controller
{
    public function index(Request $request): View
    {
        /** @var Image $image */
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

    public function test(TestImage $testImage): View
    {
        return view('images.test_data', [
            'files' => $testImage->files(),
        ]);
    }

    public function previous(): View
    {
        return view('images.previous', [
            'images' => Image::has('processingImages')->latest('created_at')->get(),
        ]);
    }

    public function show(Image $image): View
    {
        $image->load([
            'processingImages' => function ($query): void {
                $query->orderBy('mimetype', 'desc')->orderBy('original_width', 'desc');
            },
        ]);
        $processing = $image->processingImages->groupBy(['mimetype', 'original_width']);

        return view('images.show', compact(['image', 'processing']));
    }

    public function store(StoreOptimizerRequest $request, NewImage $newImage): RedirectResponse
    {
        $image = $request->image;
        $data = $request->validated();
        $image = $newImage->create($image, $data['method']);

        return redirect()->route('optimizer')->with('image', $image)->with('success', true);
    }
}
