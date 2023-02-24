<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property string $name
 * @property string $email
 * @property string $email_rfc
 * @property string $id
 * @property string $phone
 * @property string $additional_phone
 * @property string $pincode
 * @property string $description
 */
class StoreFormRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:128', 'regex:/^[A-Za-z ]{2,128}$/'],
            'email' => [
                'required',
                'string',
                'min:6',
                'max:254',
                "regex:/^[a-z][a-z0-9!#$%&\\'*+\\/=?^_`{|}~-]+(?:\\.[a-z0-9!#$%&\\'*+\\/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?$/i",
            ],
            'email_rfc' => [
                'nullable',
                'string',
                'min:6',
                'max:254',
                'regex:/^(?:[a-z0-9!#$%&\'*+\.\/\\\\=?^_`{|}~-]+(?:\.[a-z0-9!#$%&\'\"*+\.\/\\\\=?^_`{|}~-]+)*|("|\\\\)(?:[\ \x01-\x08\x0b\x0c\x0e-\x1f\x21\x23-\x5b\x5d-\x7f]|\\\\[\ \x01-\x09\x0b\x0c\x0e-\x7f])*("|\\\\))@(?:(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+(?![0-9]*$)[a-zA-Z0-9](?:[a-z0-9-]*[a-z0-9]){1,}?|\[(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?|[a-z0-9-]*[a-z0-9]:(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21-\x5a\x53-\x7f]|\\\\[\x01-\x09\x0b\x0c\x0e-\x7f])+)\])$/i',
            ],
            'id' => ['nullable', 'string', 'min:2', 'max:128', 'regex:/^[a-z0-9](?!.*__.*)([a-z0-9_)]+)?[a-z0-9]$/'],
            'phone' => [
                'required',
                'string',
                'min:12',
                'regex:/^\+38([-.\s]+)?\(?0\d{1,2}?\)?([-.\s]+)?\d{1,4}([-.\s]+)?\d{1,4}([-.\s]+)?\d{1,9}$/',
            ],
            'additional_phone' => [
                'nullable',
                'string',
                'min:7',
                'max:256',
                'regex:/^((\\+?(\\d{1,4}?([\\-.\\s]+)?\\(?\\d{1,3}?\\)?([-.\\s]+)?\\d{2,4}([-.\\s]+)?\\d{1,4}([-.\\s]+)?\\d{1,9})(?:[,;]?([\\s]+)?))+)(?<=\\d)$/m',
            ],
            'pincode' => ['required', 'string', 'min:8', 'max:9', 'regex:/^\d{4}-?\d{4}$/'],
            'description' => ['nullable', 'string', 'max:500', 'regex:/.{0,500}/'],
        ];
    }

    public function messages(): array
    {
        return [
            'id.regex' => 'Use only Latin in lower case, numbers, the sign "_"',
            'pincode.regex' => 'Pincode is valid only in the format ххххххх, хххх-хххх, where х are numbers only',
            'name.regex' => 'Only Latin and spaces are used for the name',
            'phone.regex' => 'The phone format +38 (xxx) xxx-xx-xx',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'pincode' => preg_replace('/\s/', '', (string) $this->pincode),
            'id' => preg_replace('/\s/', '', (string) $this->id),
        ]);
    }
}
