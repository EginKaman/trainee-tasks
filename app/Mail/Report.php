<?php

declare(strict_types=1);

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\{Address, Attachment, Content, Envelope};
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Date;

class Report extends Mailable
{
    use Queueable;
    use SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        private readonly string $text,
        private readonly array $logs,
        private readonly ?string $logPath
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address((string) config('mail.from.address'), (string) config('mail.from.address')),
            to: [new Address((string) config('mail.from.address'), (string) config('mail.from.address'))],
            subject: 'Report ' . Date::yesterday()->format('Y-m-d')
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.report',
            with: [
                'text' => $this->text,
                'logs' => $this->logs,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<Attachment>
     */
    public function attachments(): array
    {
        if ($this->logPath === null) {
            return [];
        }

        return [Attachment::fromPath($this->logPath)->as('laravel.log')->withMime('plain/text')];
    }
}
