<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Actions\Socialite\Callback;
use App\Actions\User\UpdateUser;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\{SocialiteCallbackRequest, SocialiteSocialRequest};
use App\Http\Requests\SocialNextRequest;
use App\Http\Resources\UserResource;
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
        return $callback->callback($request->validated('driver'));
    }
}
