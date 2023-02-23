<?php

declare(strict_types=1);

namespace App\Actions\Socialite;

use App\Http\Resources\UserResource;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\JsonResponse;
use Laravel\Socialite\Facades\Socialite;

class Callback
{
    public function callback(string $driver): UserResource|JsonResponse
    {
        try {
            /** @phpstan-ignore-next-line */
            $socialiteUser = Socialite::driver($driver)->stateless()->user();
        } catch (ClientException $exception) {
            return response()->json([
                'message' => __('Bad request'),
            ], 400);
        }

        return app(CheckUser::class)->checkOrCreate($socialiteUser, $driver);
    }
}
