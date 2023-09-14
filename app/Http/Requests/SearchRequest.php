<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enum\MediaEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SearchRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'query' => ['nullable', 'string', 'min:3', 'max:255'],
            'type' => ['nullable', 'required_with:decade', 'string', Rule::enum(MediaEnum::class)],
            'languages' => ['nullable', 'array', 'min:1'],
            'languages.*' => ['nullable', 'string', 'min:2', 'max:2'],
            'genres' => ['nullable', 'array', 'min:1'],
            'genres.*' => ['nullable', 'string', 'min:2', 'max:255'],
            'size' => ['nullable', 'integer', 'min:1', 'max:1000'],
            'year' => ['nullable', 'integer', 'min:1900', 'max:2100'],
            'decade' => [
                'nullable', 'integer',
                'in:1900,1910,1920,1930,1940,1950,1960,1970,1980,1990,2000,2010,2020,2030,2040,2050,2060,2070,2080,2090,2100',
            ],
            'countries' => ['nullable', 'array', 'min:1'],
            'countries.*' => ['nullable', 'string', 'min:2', 'max:255'],
            'place_of_birth' => ['nullable', 'string', 'min:2', 'max:255'],
        ];
    }
}
