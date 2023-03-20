<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Subscribe\{CancelSubscription, Subscribe};
use App\Exceptions\{AlreadySubscribedException, NotSubscribedException};
use App\Http\Requests\{CancelSubscribeRequest, SubscribeRequest};
use Illuminate\Http\JsonResponse;
use Stripe\Exception\ApiErrorException;
use Throwable;

class SubscribeController extends Controller
{
    public function subscribe(SubscribeRequest $request, Subscribe $subscribe): JsonResponse
    {
        $user = $request->user();

        try {
            $url = $subscribe->subscribe(
                $request->user(),
                $request->validated('subscription_id'),
                $request->validated('type_payment')
            );
        } catch (AlreadySubscribedException $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 409);
        }

        return response()->json([
            'url' => $url,
        ]);
    }

    public function cancel(CancelSubscribeRequest $request, CancelSubscription $cancelSubscribe): JsonResponse
    {
        try {
            $cancelSubscribe->cancel($request->user(), $request->validated('subscription_id'));
        } catch (Throwable|ApiErrorException $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], $exception->getCode());
        }

        return response()->json([
            'status' => __('Success'),
            'message' => __('Your subscription was canceled. Updating subscription information will be soon.'),
        ]);
    }
}
