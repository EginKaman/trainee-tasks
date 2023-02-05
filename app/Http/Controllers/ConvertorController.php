<?php

namespace App\Http\Controllers;

use App\Exceptions\UnknownProcessingException;
use App\Http\Requests\ConverterRequest;
use App\Services\Processing;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ConvertorController extends Controller
{
    public function index()
    {
        $json = new File(resource_path('schemas/schema.json'));
        $xml = new File(resource_path('schemas/schema.xsd'));
        return view('convertor', compact('json', 'xml'));
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
        $results = [];
        if ($validateFile !== true) {
            $fileErrors = $validateFile;
        }
        if ($validateFile === true) {
            $results = $processing->process(Storage::path($path));
        }

        $json = new File(resource_path('schemas/schema.json'));
        $xml = new File(resource_path('schemas/schema.xsd'));
        return view('convertor', compact('fileErrors', 'document', 'results', 'json', 'xml'));
    }

    public function jsonSchema()
    {
        return response()->file(resource_path('schemas/schema.json'));
    }

    public function xmlSchema()
    {
        return response()->file(resource_path('schemas/schema.xml'));
    }
}
