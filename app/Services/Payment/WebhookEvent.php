<?php

declare(strict_types=1);

namespace App\Services\Payment;

use App\DataTransferObjects\EventObject;
use App\Enum\{OrderStatus, PaymentStatus};
use App\Models\{Card, Order, Payment, Payment as PaymentModel, PaymentHistory, User};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class WebhookEvent
{
    private Request $request;
    private EventObject $eventObject;

    public function handle(Webhook $webhook, EventObject $eventObject, Request $request): void
    {
        $this->request = $request;
        $this->eventObject = $eventObject;

        $payment = Payment::query()->with('order')->where(
            'method_id',
            $this->eventObject->orderId
        )->where('method', $webhook->paymentMethod)->first();

        if ($payment === null) {
            return;
        }

        $this->storeEvent($payment->order);

        $event = Str::studly(Str::replace('.', '_', $this->eventObject->event));

        $this->{$event}($payment);
    }

    public function paymentIntentCreated(PaymentModel $payment): void
    {
        $payment->order->status = OrderStatus::PaymentPending->value;
        $payment->status = PaymentStatus::Created->value;
        $payment->client_secret = $this->eventObject->clientSecret;

        $this->savePayment($payment);
    }

    public function paymentIntentPaymentFailed(PaymentModel $payment): void
    {
        $payment->order->status = OrderStatus::NotPayed->value;
        $payment->status = PaymentStatus::Failed->value;
        $payment->client_secret = $this->eventObject->clientSecret;

        $this->savePayment($payment);
    }

    public function paymentIntentCanceled(PaymentModel $payment): void
    {
        $payment->order->status = OrderStatus::NotPayed->value;
        $payment->status = PaymentStatus::Canceled->value;
        $payment->client_secret = $this->eventObject->clientSecret;

        $this->savePayment($payment);
    }

    public function paymentIntentSucceeded(PaymentModel $payment): void
    {
        $payment->order->status = OrderStatus::PaymentPending->value;
        $payment->status = PaymentStatus::Success->value;
        $payment->client_secret = $this->eventObject->clientSecret;

        $this->savePayment($payment);
    }

    public function chargeRefunded(PaymentModel $payment): void
    {
        $payment->order->status = OrderStatus::PaymentPending->value;
        $payment->status = PaymentStatus::Refunded->value;
        $payment->client_secret = $this->eventObject->clientSecret;

        $this->savePayment($payment);
    }

    public function paymentMethodAttached(PaymentModel $payment): void
    {
        $user = User::where('stripe_id', $this->eventObject->customer)->first();

        if ($user) {
            $card = new Card([
                'fingerprint' => $this->eventObject->paymentMethodId,
                'type' => $this->eventObject->card->brand,
                'last_numbers' => $this->eventObject->card->last4,
            ]);
            $card->user()->associate($user);
            $card->save();
        }
    }

    private function storeEvent(Order $order): void
    {
        $webHookEventModel = new \App\Models\WebhookEvent([
            'payload' => $this->request->getContent(),
        ]);
        $webHookEventModel->order()->associate($order);
        $webHookEventModel->save();
    }

    private function savePayment(Payment $payment): void
    {
        DB::transaction(function () use ($payment): void {
            $payment->push();

            $payment->history()->save(new PaymentHistory([
                'status' => $payment->status,
            ]));
        });
    }
}
