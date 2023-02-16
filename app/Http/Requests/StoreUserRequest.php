<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *
 *     @OA\Property(property="name", type="string", format="email", description="User name"),
 *     @OA\Property(property="email", type="string", format="email", description="User email"),
 *     @OA\Property(property="phone", type="string", format="url", description="User phone number"),
 *     @OA\Property(property="photo", type="string", format="binary", description="User photo"),
 * )
 */
class StoreUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [];
    }
}
