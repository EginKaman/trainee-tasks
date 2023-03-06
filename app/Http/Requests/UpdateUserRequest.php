<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\User;
use App\Rules\BrokenImageRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\{File, Unique};

/**
 * @property User $user
 * @property string $name
 * @property string $email
 * @property string $phone
 * @property UploadedFile $photo
 * @property int $role_id
 */
class UpdateUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:60', 'regex:/^[a-zA-z](?!.*--.*)([a-zA-Z- )]+)?[a-zA-z]$/'],
            'email' => [
                'required',
                'min:6',
                'max:254',
                'not_regex:/[а-я]/i',
                'regex:/^(?:[a-z0-9!#$%&\'*+\.\/\\\\=?^_`{|}~-]+(?:\.[a-z0-9!#$%&\'\"*+\.\/\\\\=?^_`{|}~-]+)*|("|\\\\)(?:[\ \x01-\x08\x0b\x0c\x0e-\x1f\x21\x23-\x5b\x5d-\x7f]|\\\\[\ \x01-\x09\x0b\x0c\x0e-\x7f])*("|\\\\))@(?:(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+(?![0-9]*$)[a-zA-Z0-9](?:[a-z0-9-]*[a-z0-9]){1,}?|\[(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?|[a-z0-9-]*[a-z0-9]:(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21-\x5a\x53-\x7f]|\\\\[\x01-\x09\x0b\x0c\x0e-\x7f])+)\])$/i',
                'email:rfc',
                Rule::unique('users', 'email')->ignore($this->user->id),
            ],
            'phone' => ['required', 'phone:INTERNATIONAL,UA', Rule::unique('users', 'phone')->ignore($this->user->id)],
            'photo' => [
                'nullable',
                'mimes:jpg',
                new BrokenImageRule(),
                File::image()
                    ->max(5128)
                    ->dimensions(
                        Rule::dimensions()
                            ->minWidth(70)
                            ->minWidth(70)
                            ->maxHeight(5000)
                            ->maxHeight(5000)
                    ),
            ],
            'role_id' => ['required', 'integer', 'min:1', 'exists:roles,id'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'email' => preg_replace('/\s+/', '', $this->email),
        ]);
    }
}
