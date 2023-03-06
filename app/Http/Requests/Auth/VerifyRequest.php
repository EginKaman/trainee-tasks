<?php

declare(strict_types=1);

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property string $token
 */
class VerifyRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'token' => ['required', 'size:32', 'exists:App\Models\LoginToken,token'],
        ];
    }
}
