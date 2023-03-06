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
class SocialNextRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:60', 'regex:/^[a-zA-z](?!.*--.*)([a-zA-Z- )]+)?[a-zA-z]$/'],
            'phone' => [
                'required',
                'phone:INTERNATIONAL,UA',
                Rule::unique('users', 'phone')->ignore(auth('api')->id()),
            ],
            'photo' => [
                'nullable',
                'file',
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
}
