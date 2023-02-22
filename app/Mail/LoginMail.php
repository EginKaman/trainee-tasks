<?php

declare(strict_types=1);

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\{Content, Envelope};
use Illuminate\Queue\SerializesModels;

class LoginMail extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    public function __construct()
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Your login ',);
    }

    public function content(): Content
    {
        return new Content(view: 'emails.login',);
    }

    public function attachments(): array
    {
        return [];
    }
}
