<?php

declare(strict_types=1);

namespace App\Http\Requests\Images;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

/**
 * @property UploadedFile $image
 */
class StoreOptimizerRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'image' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,gif,bmp,webp',
                File::image()
                    ->max(10 * 1024)
                    ->dimensions(Rule::dimensions()->minHeight(500)->minWidth(500)),
            ],
        ];
    }
}
