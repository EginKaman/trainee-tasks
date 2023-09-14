<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enum\DomainType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @property string $code
 * @property string $type
 */
class DomainListRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'size:2', 'exists:countries,iso_2_code'],
            'type' => ['required', Rule::enum(DomainType::class)],
        ];
    }
}
