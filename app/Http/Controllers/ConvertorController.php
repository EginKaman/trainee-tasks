<?php

namespace App\Http\Controllers;

use App\Exceptions\UnknownProcessingException;
use App\Http\Requests\ConverterRequest;
use App\Services\Processing;

class ConvertorController extends Controller
{
    public function index()
    {
        return view('convertor');
    }

    public function store(ConverterRequest $request, Processing $processing)
    {
        $file = $request->file('file');
        try {
            $processing->setMimeType($file->getMimeType());
        } catch (UnknownProcessingException $exception) {
            return redirect()->route('convertor')->with('failed', true);
        }
        $validateFile = $processing->validate($file);
        $fileErrors = [];
        if ($validateFile !== true) {
            $fileErrors = $validateFile;
        }
        return view('convertor', compact('fileErrors'));
    }
}
