<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;

/**
 * @property UploadedFile $document
 * @property string $reader
 */
class ConverterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'document' => ['required', 'file', 'mimes:csv,json,xml', 'max:1024'],
            'reader' => ['nullable', 'string', 'in:simplexml,xmlreader'],
        ];
    }
}
