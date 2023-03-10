<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\{Address, Content, Envelope};
use Illuminate\Queue\SerializesModels;

class LoginMail extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public User $user,
        public string $token
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address((string) config('mail.from.address'), (string) config('mail.from.address')),
            to: [new Address($this->user->email, $this->user->email)],
            subject: 'Verify login'
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.login', with: [
            'name' => $this->user->name,
            'hash' => $this->token,
        ]);
    }

    public function attachments(): array
    {
        return [];
    }
}
