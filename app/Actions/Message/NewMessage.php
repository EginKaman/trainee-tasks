<?php

declare(strict_types=1);

namespace App\Actions\Message;

use App\Mail\Feedback;
use App\Models\Message;
use Illuminate\Support\Facades\Mail;

class NewMessage
{
    public function create(array $data): Message
    {
        $message = new Message($data);
        $message->save();

        Mail::mailer($message->method)
            ->send(new Feedback($message));

        return $message;
    }
}
