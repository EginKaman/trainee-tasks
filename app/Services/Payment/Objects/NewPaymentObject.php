<?php

declare(strict_types=1);

namespace App\Services\Payment\Objects;

use App\Models\{Card, User};

class NewPaymentObject
{
    public int $amount;
    public ?User $user;
    public bool $saveCard;
    public ?Card $card;

    public function __construct(int $amount, ?User $user = null, bool $saveCard = false, ?Card $card = null)
    {
        $this->amount = $amount;
        $this->user = $user;
        $this->saveCard = $saveCard;
        $this->card = $card;
    }
}
