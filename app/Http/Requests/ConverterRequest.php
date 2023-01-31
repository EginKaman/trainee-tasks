<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConverterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'file' => [
                'required',
                'file',
                'mimes:csv,json,xml',
                'max:1024'
            ],
            'method' => [
                'nullable',
                'string',
                'in:simplexml,readerxml'
            ]
        ];
    }
}
