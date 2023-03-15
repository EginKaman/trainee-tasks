<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DestroyCardRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'card' => ['required', 'int', 'min:1'],
        ];
    }
    protected function prepareForValidation(): void
    {
        $this->merge([
            'card' => $this->route('card'),
        ]);
    }
}
