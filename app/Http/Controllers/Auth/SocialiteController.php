<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Actions\Socialite\{Callback, CheckUser};
use App\Actions\User\UpdateUser;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\{SocialiteCallbackRequest, SocialiteSocialRequest};
use App\Http\Requests\SocialNextRequest;
use App\Http\Resources\UserResource;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\JsonResponse;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    public function social(SocialiteSocialRequest $request): JsonResponse
    {
        return response()->json([
            /** @phpstan-ignore-next-line */
            'url' => Socialite::driver($request->validated('driver'))->stateless()->redirect()->getTargetUrl(),
        ]);
    }

    public function next(SocialNextRequest $request, UpdateUser $updateUser): UserResource
    {
        return new UserResource($updateUser->update($request->validated(), auth('api')->user()));
    }

    public function callback(SocialiteCallbackRequest $request, Callback $callback): UserResource|JsonResponse
    {
        $driver = $request->validated('driver');

        try {
            /** @phpstan-ignore-next-line */
            $socialiteUser = Socialite::driver($driver)->stateless()->user();
        } catch (ClientException $exception) {
            $message = json_decode((string) $exception->getResponse()->getBody(), false);

            return response()->json([
                'message' => $message->error->message ?? $message->error_description,
            ], $exception->getCode());
        }

        $user = $callback->callback($socialiteUser, $driver);

        $additional = [
            /** @phpstan-ignore-next-line */
            'access_token' => auth('api')->login($user),
            'token_type' => 'bearer',
            /** @phpstan-ignore-next-line */
            'expires_in' => auth('api')->factory()->getTTL() * 60,
        ];
        if ($user->wasRecentlyCreated) {
            $additional['message'] = __('You are successfully first-step register, go by two-step register');
        }

        return (new UserResource($user))->additional($additional);
    }
}
