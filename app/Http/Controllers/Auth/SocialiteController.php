<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\{JsonResponse, Request};
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    public function social(): JsonResponse
    {
        return response()->json([
            'url' => Socialite::driver('facebook')->redirect()->getTargetUrl(),
        ]);
    }

    public function callback(Request $request): JsonResponse
    {
        return response()->json(Socialite::driver('facebook')->user());
    }
}
