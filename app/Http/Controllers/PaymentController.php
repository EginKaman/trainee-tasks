<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Exceptions\{UnknownPaymentMethodException};
use App\Http\Requests\{RefundPaymentRequest, StorePaymentRequest};
use App\Services\Payment\{NewPayment, Webhook, WebhookEvent};
use App\Services\Payment\{NewRefund};
use Illuminate\Http\{JsonResponse, Request, Response};
use Stripe\{Exception\SignatureVerificationException};
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;
use UnexpectedValueException;

class PaymentController extends Controller
{
    public function store(StorePaymentRequest $request, NewPayment $payment): JsonResponse
    {
        try {
            $response = $payment->create($request->user(), $request->validated());
        } catch (UnknownPaymentMethodException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        } catch (Throwable $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }

        return response()->json($response);
    }

    public function refund(RefundPaymentRequest $request, NewRefund $refund): JsonResponse
    {
        try {
            $refund->refund($request->validated('order_id'));
        } catch (HttpException $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], $exception->getCode());
        }

        return response()->json([
            'message' => __('Refund success'),
        ]);
    }

    public function webhook(Request $request, string $method, WebhookEvent $webhookEvent): JsonResponse|Response
    {
        try {
            $webhook = new Webhook($method);
        } catch (HttpException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], $e->getCode());
        }

        try {
            $eventObject = $webhook->createEvent($request);
        } catch (HttpException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], $e->getCode());
        } catch (UnexpectedValueException|SignatureVerificationException|Throwable $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }

        if (!isset($eventObject->orderId)) {
            return response()->noContent();
        }

        $webhookEvent->handle($webhook, $eventObject, $request);

        return response()->noContent();
    }
}
