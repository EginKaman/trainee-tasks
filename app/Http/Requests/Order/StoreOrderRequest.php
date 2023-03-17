<?php

declare(strict_types=1);

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property array $products
 */
class StoreOrderRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'products' => ['required', 'array', 'min:1'],
            'products.*.id' => ['required', 'integer', 'min:1', 'exists:products,id'],
            'products.*.quantity' => ['required', 'integer', 'min:1', 'max:99'],
        ];
    }
}
