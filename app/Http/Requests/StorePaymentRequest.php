<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @property int $order_id
 * @property string $type_payment
 * @property ?bool $save_card
 * @property ?int $card_id
 */
class StorePaymentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'order_id' => [
                'required',
                'integer',
                'min:1',
                Rule::exists('orders', 'id')->where('user_id', auth()->id()),
            ],
            'type_payment' => ['required', 'string', 'in:stripe,paypal'],
            'save_card' => ['nullable', 'boolean'],
            'card_id' => [
                'nullable',
                'integer',
                'min:1',
                Rule::exists('cards', 'id')->where('user_id', auth()->id()),
            ],
        ];
    }

    public function authorize(): bool
    {
        return auth('api')->check();
    }
}
