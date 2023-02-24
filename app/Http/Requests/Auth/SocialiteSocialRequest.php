<?php

declare(strict_types=1);

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class SocialiteSocialRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'driver' => ['required', 'string', 'in:google,facebook'],
        ];
    }
}
