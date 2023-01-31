<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class FeedbackRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'email',
                'string',
                'min:6',
                'max:254'
            ],
            'name' => [
                'required',
                'string',
                'regex:/^[a-zA-Z][a-zA-Z0-9- ]+$/',
                'min:2',
                'max:60'
            ],
            'text' => [
                'required',
                'string',
                'max:500'
            ],
            'method' => [
                'required',
                'string',
                Rule::in(['smtp', 'sendgrid'])
            ],
            'g-recaptcha-response' => 'recaptcha',
        ];
    }
}
