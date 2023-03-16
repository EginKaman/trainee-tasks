<?php

declare(strict_types=1);

namespace App\Services\Payment;

use App\DataTransferObjects\EventObject;
use App\Enum\OrderStatus;
use App\Models\{Card, Payment, Payment as PaymentModel, PaymentHistory, User};
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class WebhookEvent
{
    public static function handle(Webhook $webhook, EventObject $eventObject): void
    {
        $payment = Payment::where(
            'method_id',
            $eventObject->orderId
        )->where('method', $webhook->paymentMethod)->first();

        if ($payment === null) {
            return;
        }

        $event = Str::studly(Str::replace('.', '_', $eventObject->event));

        self::{$event}();
    }
    public static function paymentIntentCreated(PaymentModel $payment, EventObject $eventObject): void
    {
        $payment->order->status = OrderStatus::PaymentPending;
        $payment->status = $eventObject->status;
        $payment->client_secret = $eventObject->clientSecret;

        DB::transaction(function () use ($payment, $eventObject): void {
            $payment->push();

            $payment->history()->save(new PaymentHistory([
                'status' => $eventObject->status,
            ]));
        });
    }

    public static function paymentIntentPaymentFailed(PaymentModel $payment, EventObject $eventObject): void
    {
        $payment->order->status = OrderStatus::NotPayed;
        $payment->status = $eventObject->status;
        $payment->client_secret = $eventObject->clientSecret;

        DB::transaction(function () use ($payment, $eventObject): void {
            $payment->push();

            $payment->history()->save(new PaymentHistory([
                'status' => $eventObject->status,
            ]));
        });
    }

    public static function paymentIntentSucceeded(PaymentModel $payment, EventObject $eventObject): void
    {
        $payment->order->status = OrderStatus::PaymentPending;
        $payment->status = $eventObject->status;
        $payment->client_secret = $eventObject->clientSecret;

        DB::transaction(function () use ($payment, $eventObject): void {
            $payment->push();

            $payment->history()->save(new PaymentHistory([
                'status' => $eventObject->status,
            ]));
        });
    }

    public static function chargeRefunded(PaymentModel $payment, EventObject $eventObject): void
    {
        $payment->order->status = OrderStatus::PaymentPending;
        $payment->status = $eventObject->status;
        $payment->client_secret = $eventObject->clientSecret;

        DB::transaction(function () use ($payment, $eventObject): void {
            $payment->push();

            $payment->history()->save(new PaymentHistory([
                'status' => $eventObject->status,
            ]));
        });
    }

    public static function paymentMethodAttached(PaymentModel $payment, EventObject $eventObject): void
    {
        $user = User::where('stripe_id', $eventObject->customer)->first();

        if ($user) {
            $card = new Card([
                'fingerprint' => $eventObject->paymentMethodId,
                'type' => $eventObject->card->brand,
                'last_numbers' => $eventObject->card->last4,
            ]);
            $card->user()->associate($user);
            $card->save();
        }
    }
}
