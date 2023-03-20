<?php

declare(strict_types=1);

namespace App\DataTransferObjects;

class CreatedSubscriptionObject
{
    public function __construct(
        public string $url,
        public ?string $subscriptionId = null
    ) {
    }
}
