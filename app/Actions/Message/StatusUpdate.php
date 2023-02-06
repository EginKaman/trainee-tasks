<?php

declare(strict_types=1);

namespace App\Actions\Message;

use App\Models\Message;

class StatusUpdate
{
    /**
     * @param string[] $response
     */
    public function update(array $response): void
    {
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
    }
}
