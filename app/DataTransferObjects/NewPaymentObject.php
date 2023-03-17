<?php

declare(strict_types=1);

namespace App\DataTransferObjects;

use App\Models\{Card, User};

class NewPaymentObject
{
    public function __construct(
        public int $amount,
        public ?User $user = null,
        public bool $saveCard = false,
        public ?Card $card = null
    ) {
    }
}
