<?php

declare(strict_types=1);

namespace App\Services\Payment;

use App\DataTransferObjects\EventObject;
use Illuminate\Http\Request;
use Stripe\Exception\SignatureVerificationException;
use Throwable;

class Webhook extends Payment
{
    /**
     * @throws SignatureVerificationException|Throwable
     */
    public function createEvent(Request $request): EventObject
    {
        return $this->client->createEvent($request);
    }
}
