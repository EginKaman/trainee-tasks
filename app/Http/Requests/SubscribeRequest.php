<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property int $subscription_id
 * @property string $type_payment
 */
class SubscribeRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'subscription_id' => ['required', 'int', 'min:1', 'exists:App\Models\Subscription,id'],
            'type_payment' => ['required', 'string', 'in:stripe,paypal'],
        ];
    }
}
