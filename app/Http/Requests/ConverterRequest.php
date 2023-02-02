<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;

/**
 * @property UploadedFile $document
 */
class ConverterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'document' => [
                'required',
                'file',
                'mimes:csv,json,xml',
                'max:1024'
            ],
            'reader' => [
                'nullable',
                'string',
                'in:simplexml,xmlreader'
            ]
        ];
    }
}
