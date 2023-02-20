<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

/**
 * @property string $name
 * @property string $email
 * @property string $phone
 * @property UploadedFile $photo
 */
class StoreUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:60'],
            'email' => ['required', 'email:rfc', 'min:6', 'max:128'],
            'phone' => ['required', 'phone:INTERNATIONAL,UK'],
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
        ];
    }
}
