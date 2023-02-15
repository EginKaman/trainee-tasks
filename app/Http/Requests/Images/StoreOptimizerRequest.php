<?php

declare(strict_types=1);

namespace App\Http\Requests\Images;

use App\Facades\FileHelper;
use App\Rules\BrokenImageRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

/**
 * @property UploadedFile $image
 */
class StoreOptimizerRequest extends FormRequest
{
    public int $maxFileSize = 10240;

    public function rules(): array
    {
        return [
            'image' => [
                'required',
                new BrokenImageRule(),
                'image',
                'mimes:jpg,jpeg,png,gif,bmp,webp',
                File::image()
                    ->max($this->maxFileSize)
                    ->dimensions(Rule::dimensions()->minHeight(500)->minWidth(500)),
            ],
            'timezone' => ['required', 'int'],
        ];
    }

    public function messages(): array
    {
        return [
            'image.max' => "Your image is {$this->getSizeImage()} in weight. Select a image less than {$this->getMaxSize()}",
            'image.dimensions' => "Your image is {$this->getDimensions()} in size. Select an image more than 500x500px",
        ];
    }

    protected function getSizeImage(): string
    {
        if ($this->image === null) {
            return '';
        }

        return FileHelper::sizeForHumans($this->image->getSize());
    }

    protected function getMaxSize(): string
    {
        return FileHelper::sizeForHumans($this->maxFileSize * 1024);
    }

    protected function getDimensions(): string
    {
        $image = getimagesize($this->image->getRealPath());
        if (!$image) {
            return '';
        }

        return "{$image[0]}x{$image[1]}px";
    }
}
