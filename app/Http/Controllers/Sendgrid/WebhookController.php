<?php declare(strict_types=1);

namespace App\Http\Controllers\Sendgrid;

use App\Http\Controllers\Controller;
use App\Models\Message;
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
        /** @var string[] $response */
        $response = $request->json();

        /** @var string[] $item */
        foreach ($response as $item) {
            $email = $item['email'];
            if ($item['event'] === 'delivered') {
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
