<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Document\{NewDocument, TestData};
use App\Exceptions\UnknownProcessingException;
use App\Http\Requests\ConverterRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\{File, RedirectResponse, Request};
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ConvertorController extends Controller
{
    public function index(Request $request): View
    {
        $json = new File(resource_path('schemas/schema.json'));
        $xml = new File(resource_path('schemas/schema.xsd'));

        $results = [];
        if ($request->session()->has('results')) {
            $results = $request->session()->get('results');
            $results = json_decode($results);
        }

        $fileErrors = $request->session()->get('fileErrors');

        $files = $request->session()->get('files');

        $urls = $request->session()->get('urls');

        $originalName = $request->session()->get('originalName');

        $testData = app(TestData::class)->files();

        return view(
            'convertor',
            compact(['json', 'xml', 'fileErrors', 'originalName', 'results', 'files', 'urls', 'testData'])
        );
    }

    public function store(ConverterRequest $request, NewDocument $newDocument): RedirectResponse
    {
        $document = $request->document;
        $originalName = $request->document->getClientOriginalName();

        $results = [];
        $fileErrors = [];
        $files = [];
        $urls = [];

        try {
            $newDocument->store($document);
        } catch (UnknownProcessingException $exception) {
            Log::error($exception->getMessage(), $exception->getTrace());

            return redirect()->route('convertor')->with('failure', true);
        }

        if ($newDocument->isValid()) {
            $results = json_encode($newDocument->results());
            $files = $newDocument->getFiles();
            $urls = $newDocument->getUrls();
        } else {
            $fileErrors = $newDocument->getErrors();
        }

        return redirect()->route('convertor')->with(
            compact(['fileErrors', 'originalName', 'results', 'files', 'urls'])
        );
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
