<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\DestroyCardRequest;
use App\Http\Resources\CardCollection;
use App\Repositories\CardRepository;
use Illuminate\Http\{Request, Response};

class CardController extends Controller
{
    public function index(Request $request): CardCollection
    {
        return new CardCollection(CardRepository::getUserCards($request->user()));
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
