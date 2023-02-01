<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class FeedbackRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $text = preg_replace('/ +/m', ' ', $this->text);
        $text = preg_replace('/[\r\n]{2,}+/m', "\r\n\r\n", $text);
        $text = preg_replace('/[\n]{2,}+/m', "\n\n", $text);
        $email = preg_replace('/\s+(?=(?:(?:[^"]*"){2})*[^"]*"[^"]*)/', ' ', $this->email);
        $this->merge([
            'email' => $email,
            'name' => preg_replace('/\s+/', ' ', $this->name),
            'text' => $text
        ]);
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
                'email:rfc,dns',
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
            'g-recaptcha-response' => 'required|recaptchav3:feedback,0.5',
        ];
    }
}
