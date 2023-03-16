<?php

declare(strict_types=1);

namespace App\DataTransferObjects;

use Stripe\Card;

class EventObject
{
    public function __construct(
        public ?string $event = null,
        public ?string $orderId = null,
        public ?string $status = null,
        public ?string $clientSecret = null,
        public ?string $customer = null,
        public ?string $paymentMethodId = null,
        public ?Card $card = null
    ) {
    }
}
