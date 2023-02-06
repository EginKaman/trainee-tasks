<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Routing\Exceptions\InvalidSignatureException;
use SendGrid\EventWebhook\EventWebhook;
use SendGrid\EventWebhook\EventWebhookHeader;

class SignedWebhookMiddleware
{
    /**
     * @param Request $request
     * @param Closure $next
     * @param EventWebhook $webhook
     * @return mixed
     */
    public function handle(Request $request, Closure $next, EventWebhook $webhook): mixed
    {
        $publicKey = $webhook->convertPublicKeyToECDSA(config('services.sendgrid.webhook.verification_key'));
        $signature = $request->header(EventWebhookHeader::SIGNATURE);
        $timestamp = $request->header(EventWebhookHeader::TIMESTAMP);
        $verify = $webhook->verifySignature($publicKey, $request->getContent(), $signature, $timestamp);
        if (!$verify) {
            throw new InvalidSignatureException();
        }
        return $next($request);
    }
}
