<?php

declare(strict_types=1);

namespace App\Http\Controllers\Sendgrid;

use App\Actions\Message\StatusUpdate;
use App\Http\Controllers\Controller;
use Illuminate\Http\{Request, Response};

class WebhookController extends Controller
{
    public function update(Request $request): Response
    {
        app(StatusUpdate::class)->update($request->json());

        return response('');
    }
}
