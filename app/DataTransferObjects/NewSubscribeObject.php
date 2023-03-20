<?php

declare(strict_types=1);

namespace App\DataTransferObjects;

use App\Models\User;

class NewSubscribeObject
{
    public function __construct(
        public ?string $planId = null,
        public ?User $user = null
    ) {
    }
}
