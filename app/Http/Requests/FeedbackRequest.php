<?php
declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @property string $email
 * @property string $name
 * @property string $text
 * @property string $method
 * @property string $g-recaptcha-response
 */
class FeedbackRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'email:rfc,dns', 'string', 'min:6', 'max:254'],
            'name' => ['required', 'string', 'min:2', 'max:60', 'regex:/^[a-zA-Z][a-zA-Z- ]+$/'],
            'text' => ['required', 'string', 'max:500'],
            'method' => ['required', 'string', Rule::in(['smtp', 'sendgrid'])],
            'g-recaptcha-response' => 'required|recaptchav3:feedback,0.5',
        ];
    }

    /**
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'name.regex' => 'The name field must start with letter and allow only latin symbols, space and "-"',
        ];
    }

    /**
     * @return string[]
     */
    public function attributes(): array
    {
        return [
            'text' => 'message',
        ];
    }

    protected function prepareForValidation(): void
    {
        $text = preg_replace('/ +/m', ' ', $this->text);
        $text = preg_replace('/[\r\n]{2,}+/m', "\r\n\r\n", $text);
        $text = preg_replace('/\n{2,}+/m', "\n\n", $text);
        $email = $this->email;
        if (mb_substr_count($email, '"') === 2) {
            $email = preg_replace('/\s+(?=(?:(?:[^"]*"){2})*[^"]*"[^"]*)/', ' ', $email);
        } else {
            $email = preg_replace('/\s+/', '', $email);
        }
        $this->merge([
            'email' => $email,
            'name' => preg_replace('/\s+/', ' ', $this->name),
            'text' => $text,
        ]);
    }
}
