<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Exceptions\UnknownProcessingException;
use App\Http\Requests\ConverterRequest;
use App\Services\Processing;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\{Factory, View};
use Illuminate\Http\{File, RedirectResponse};
use Illuminate\Support\Facades\{Log, Storage};
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ConvertorController extends Controller
{
    public function index(): Application|Factory|View
    {
        $json = new File(resource_path('schemas/schema.json'));
        $xml = new File(resource_path('schemas/schema.xsd'));

        return view('convertor', compact('json', 'xml'));
    }

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
        $files = [];
        $urls = [];
        if ($validateFile !== true) {
            $fileErrors = $validateFile;
        }
        if ($validateFile === true) {
            $results = $processing->process(Storage::path($path));
            $hash = Str::random(32);

            $processing->write($results, $hash);

            $files = [
                'processing_results_simple' => new File(
                    storage_path("app/public/documents/{$hash}/processing results simple.xml")
                ),
                'processing_results_writer' => new File(
                    storage_path("app/public/documents/{$hash}/processing results writer.xml")
                ),
                'processing_results_json' => new File(
                    storage_path("app/public/documents/{$hash}/processing results.json")
                ),
                'processing_results_csv' => new File(
                    storage_path("app/public/documents/{$hash}/processing results.csv")
                ),
            ];
            $urls = [
                'processing_results_simple' => Storage::url("documents/{$hash}/processing results simple.xml"),
                'processing_results_writer' => Storage::url("documents/{$hash}/processing results writer.xml"),
                'processing_results_json' => Storage::url("documents/{$hash}/processing results.json"),
                'processing_results_csv' => Storage::url("documents/{$hash}/processing results.csv"),
            ];
        }

        $json = new File(resource_path('schemas/schema.json'));
        $xml = new File(resource_path('schemas/schema.xsd'));

        return view('convertor', compact(['fileErrors', 'document', 'results', 'json', 'xml', 'files', 'urls']));
    }

    public function jsonSchema(): BinaryFileResponse
    {
        return response()->download(resource_path('schemas/schema.json'), 'schema.json', [
            'Content-Type: application/json',
        ]);
    }

    public function xmlSchema(): BinaryFileResponse
    {
        return response()->download(resource_path('schemas/schema.xsd'), 'schema.xsd', [
            'Content-Type: application/xml',
        ]);
    }
}
