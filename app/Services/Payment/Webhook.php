<?php

declare(strict_types=1);

namespace App\Services\Payment;

use Illuminate\Http\Request;

class Webhook extends Payment
{
    public function validateSignature(Request $request): void
    {
        $this->client->validateSignature($request);
    }
}
