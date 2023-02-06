<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Exceptions\UnknownProcessingException;
use App\Http\Requests\ConverterRequest;
use App\Services\Processing;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\File;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\{Log, Storage};
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ConvertorController extends Controller
{
    /**
     * @return Application|Factory|View
     */
    public function index(): Application|Factory|View
    {
        $json = new File(resource_path('schemas/schema.json'));
        $xml = new File(resource_path('schemas/schema.xsd'));

        return view('convertor', compact('json', 'xml'));
    }

    /**
     * @param ConverterRequest $request
     * @param Processing $processing
     * @return Application|Factory|View|RedirectResponse
     */
    public function store(ConverterRequest $request, Processing $processing): Application|Factory|View|RedirectResponse
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

        return view('convertor', compact('fileErrors', 'document', 'results', 'json', 'xml'))
            ->with('success', true);
    }

    /**
     * @return BinaryFileResponse
     */
    public function jsonSchema(): BinaryFileResponse
    {
        return response()->download(resource_path('schemas/schema.json'), 'schema.json', [
            'Content-Type: application/json'
        ]);
    }

    /**
     * @return BinaryFileResponse
     */
    public function xmlSchema(): BinaryFileResponse
    {
        return response()->download(resource_path('schemas/schema.xsd'), 'schema.xsd', [
            'Content-Type: application/xml'
        ]);
    }
}
