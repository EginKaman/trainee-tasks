<?php

namespace App\Http\Controllers;

use App\Exceptions\UnknownProcessingException;
use App\Http\Requests\ConverterRequest;
use App\Services\Processing;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ConvertorController extends Controller
{
    public function index()
    {
        return view('convertor');
    }

    public function store(ConverterRequest $request, Processing $processing)
    {
        $document = $request->document;
        try {
            $processing->setMimeType($document->getMimeType());
        } catch (UnknownProcessingException $exception) {
            Log::error($exception->getMessage(), $exception->getTrace());
            return redirect()->route('convertor')->with('failure', true);
        }
        $path = $document->store('documents');
        $validateFile = $processing->validate(Storage::path($path));
        $fileErrors = [];
        if ($validateFile !== true) {
            $fileErrors = $validateFile;
        }
        return view('convertor', compact('fileErrors'));
    }
}
