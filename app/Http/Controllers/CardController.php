<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\CardCollection;
use App\Models\Card;
use Illuminate\Http\Response;

class CardController extends Controller
{
    public function index(): CardCollection
    {
        return new CardCollection(auth('api')->user()->cards()->get());
    }

    public function destroy(Card $card): Response
    {
        if ($card->delete()) {
            return response(__('Deleted success'), 204);
        }

        return response(__('Something went wrong'), 503);
    }
}
