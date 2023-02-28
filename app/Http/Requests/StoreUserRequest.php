<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Rules\File;
use Illuminate\Validation\{Rule, ValidationException};

/**
 * @property string $name
 * @property string $email
 * @property string $phone
 * @property UploadedFile $photo
 * @property int $role_id
 */
class StoreUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:60', 'regex:/^[A-Za-z- ]+$/'],
            'email' => ['required', 'email:rfc', 'unique:users,email', 'min:6', 'max:128'],
            'phone' => ['required', 'phone:INTERNATIONAL,UA', 'unique:users,phone'],
            'photo' => [
                'required',
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
