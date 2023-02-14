<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Document\NewDocument;
use App\Exceptions\UnknownProcessingException;
use App\Http\Requests\ConverterRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\File;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ConvertorController extends Controller
{
    public function index(Request $request): View
    {
        $json = new File(resource_path('schemas/schema.json'));
        $xml = new File(resource_path('schemas/schema.xsd'));
        $results = $request->session()->get('results');
        $fileErrors = $request->session()->get('fileErrors');
        $files = $request->session()->get('files');
        $urls = $request->session()->get('urls');
        $document = $request->session()->get('document');
        return view('convertor', compact(['json', 'xml', 'fileErrors', 'document', 'results', 'files', 'urls']));
    }

    public function store(ConverterRequest $request, NewDocument $newDocument): RedirectResponse
    {
        $document = $request->document;
        $results = [];
        $fileErrors = [];
        try {
            $newDocument->store($document);
        } catch (UnknownProcessingException $exception) {
            Log::error($exception->getMessage(), $exception->getTrace());
            return redirect()->route('convertor')->with('failure', true);
        }
        if ($newDocument->isValid()) {
            $results = $newDocument->results();
        } else {
            $fileErrors = $newDocument->getErrors();
        }
        $files = $newDocument->getFiles();
        $urls = $newDocument->getUrls();
        return redirect()->route('convertor')->with(compact(['fileErrors', 'document', 'results', 'files', 'urls']));
    }

    public function jsonSchema(): BinaryFileResponse
    {
        return response()->download(resource_path('schemas/schema.json'), 'schema.json', [
            'Content-Type: application/json'
        ]);
    }

    public function xmlSchema(): BinaryFileResponse
    {
        return response()->download(resource_path('schemas/schema.xsd'), 'schema.xsd', [
            'Content-Type: application/xml'
        ]);
    }
}
