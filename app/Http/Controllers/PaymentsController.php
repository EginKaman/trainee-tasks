<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Payment\NewPayment;
use App\Http\Requests\StorePaymentRequest;
use App\Models\{Payment, PaymentHistory};
use Illuminate\Http\{JsonResponse, Request};
use Illuminate\Support\Str;

class PaymentsController extends Controller
{
    public function store(StorePaymentRequest $request, NewPayment $payment): JsonResponse
    {
        return $payment->create($request);
    }

    public function refund(Request $request): void
    {
    }

    public function webhook(Request $request, string $method): JsonResponse
    {
        try {
            $event = \Stripe\Webhook::constructEvent(
                file_get_contents('php://input'),
                $request->server('HTTP_STRIPE_SIGNATURE'),
                config
                (
                    'services.stripe.webhook_secret'
                )
            );
        } catch (\UnexpectedValueException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }

        if (Str::startsWith($event->type, 'payment_intent.')) {
            /** @phpstan-ignore-next-line  */
            $paymentIntent = $event->data->object;

            $payment = Payment::where('method_id', $paymentIntent->id)->where('method', 'stripe')->first();

            $payment->status = $paymentIntent->status;
            $payment->client_secret = $paymentIntent->client_secret;

            $payment->save();

            $payment->history()->save(new PaymentHistory([
                'status' => $paymentIntent->status,
            ]));

            return response()->json([
                'message' => 'success',
            ]);
        }
        if ($event->type === 'charge.refunded') {
            /** @phpstan-ignore-next-line  */
            $refund = $event->data->object;

            $payment = Payment::where('method_id', $refund->payment_intent)->where('method', 'stripe')->first();

            $payment->status = 'refunded';
            $payment->client_secret = $refund->client_secret;

            $payment->save();

            $payment->history()->save(new PaymentHistory([
                'status' => 'refunded',
            ]));

            return response()->json([
                'message' => 'success',
            ]);
        }

        return response()->json([
            'message' => 'Received unknown event type',
            'type' => $event->type,
        ], 400);
    }
}
