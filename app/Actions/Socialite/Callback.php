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
            $message = json_decode((string) $exception->getResponse()->getBody(), false);

            return response()->json([
                'message' => $message->error->message ?? $message->error_description,
            ], $exception->getCode());
        }

        return app(CheckUser::class)->checkOrCreate($socialiteUser, $driver);
    }
}
