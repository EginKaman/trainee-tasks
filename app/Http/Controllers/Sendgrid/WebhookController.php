<?php

declare(strict_types=1);

namespace App\Http\Controllers\Sendgrid;

use App\Actions\Message\StatusUpdate;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\{Request, Response};

class WebhookController extends Controller
{
    /**
     * @return Application|Response|ResponseFactory
     */
    public function update(Request $request)
    {
        app(StatusUpdate::class)->update($request->json());

        return response('');
    }
}
