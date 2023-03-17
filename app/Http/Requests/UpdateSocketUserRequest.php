<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Rules\BrokenImageRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

/**
 * @property string $name
 * @property UploadedFile $photo
 * @property bool $online
 */
class UpdateSocketUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:60', 'regex:/^[a-zA-z](?!.*--.*)([a-zA-Z- )]+)?[a-zA-z]$/'],
            'photo' => [
                'nullable',
                'mimes:jpg',
                new BrokenImageRule(),
                File::image()
                    ->max(5128)
                    ->dimensions(
                        Rule::dimensions()
                            ->minWidth(50)
                            ->minWidth(50)
                            ->maxHeight(5000)
                            ->maxHeight(5000)
                    ),
            ],
            'online' => ['required', 'boolean'],
        ];
    }
}
