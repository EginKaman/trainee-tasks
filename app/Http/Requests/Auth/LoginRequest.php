<?php

declare(strict_types=1);

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property ?string $email
 * @property ?string $token
 */
class LoginRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => ['required_without:token', 'min:6', 'email:rfc', 'exists:App\Models\User,email'],
            'token' => ['required_without:email', 'size:32', 'exists:App\Models\LoginToken,token'],
        ];
    }
}
