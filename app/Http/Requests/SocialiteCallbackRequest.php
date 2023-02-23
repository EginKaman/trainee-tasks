<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property string $driver
 * @property string $code
 */
class SocialiteCallbackRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'driver' => ['required', 'string', 'in:google,facebook'],
            'code' => ['required', 'string', 'min:50'],
        ];
    }
}
