<?php

declare(strict_types=1);

namespace App\Http\Requests\Auth;

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
            'code' => ['required', 'string', 'regex:/^[a-z0-9_\-\/]+$/i', 'min:50'],
        ];
    }
}
