<?php declare(strict_types=1);

namespace App\Listeners;

use App\Models\Message;
use Illuminate\Mail\Events\MessageSent;

class SuccessfulSentMessage
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     */
    public function handle(MessageSent $event): void
    {
        if (!isset($event->data['id']) || $event->data['method'] === 'sendgrid') {
            return;
        }
        $message = Message::find($event->data['id']);
        $message->success = true;
        $message->save();
    }
}
