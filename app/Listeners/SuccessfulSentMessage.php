<?php

namespace App\Listeners;

use App\Models\Message;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Events\MessageSent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SuccessfulSentMessage
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param MessageSent $event
     * @return void
     */
    public function handle(MessageSent $event)
    {
        if (!isset($event->data['id']) || $event->data['method'] === 'sendgrid') {
            return;
        }
        $message = Message::find($event->data['id']);
        $message->success = true;
        $message->save();
    }
}
