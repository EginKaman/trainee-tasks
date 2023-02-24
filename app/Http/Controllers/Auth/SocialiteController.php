<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Actions\Socialite\Callback;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\{SocialiteCallbackRequest, SocialiteSocialRequest};
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

    public function callback(SocialiteCallbackRequest $request): UserResource|JsonResponse
    {
        return app(Callback::class)->callback($request->validated('driver'));
    }
}
