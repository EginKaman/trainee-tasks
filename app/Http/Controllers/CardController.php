<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Card\UserCard;
use App\Http\Requests\DestroyCardRequest;
use App\Http\Resources\CardCollection;
use App\Repositories\CardRepository;
use Illuminate\Http\Response;

class CardController extends Controller
{
    public function index(UserCard $userCard): CardCollection
    {
        return new CardCollection(CardRepository::getUserCards(auth('api')->user()));
    }

    public function destroy(DestroyCardRequest $request, int $card): Response
    {
        $userCard = CardRepository::getUserCard($request->user('api'), $card);
        if ($userCard->delete()) {
            return response()->noContent();
        }

        return response(__('Something went wrong'), 503);
    }
}
