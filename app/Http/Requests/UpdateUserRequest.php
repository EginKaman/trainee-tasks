<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\User;
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
                'email:rfc',
                Rule::unique('users', 'email')->ignore($this->user->id),
                'min:6',
                'max:128'
            ],
            'phone' => ['required', 'phone:INTERNATIONAL,UA', Rule::unique('users', 'phone')->ignore($this->user->id)],
            'photo' => [
                'nullable',
                'mimes:jpg',
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
}
