<?php

namespace App\Http\Controllers\Sendgrid;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function update(Request $request)
    {
        $response = $request->json();

        foreach ($response as $item) {
            $email = $item['email'];
            if ($item['delivered']) {
                $message = Message::query()
                    ->where('email', $email)
                    ->where('method', 'sendgrid')
                    ->latest()
                    ->first();
                if ($message === null) {
                    continue;
                }
                $message->success = true;
                $message->save();
            }
        }


        return response('');
    }
}
