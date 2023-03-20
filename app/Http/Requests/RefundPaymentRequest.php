<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @property int $order_id
 */
class RefundPaymentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'order_id' => [
                'required',
                'int',
                'min:1',
                Rule::exists('orders', 'id')->where(fn (Builder $query) => $query->where('user_id', $this->user()->id)),
            ],
        ];
    }
}
