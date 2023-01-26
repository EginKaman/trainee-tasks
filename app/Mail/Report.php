<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Date;

class Report extends Mailable
{
    use Queueable;
    use SerializesModels;

    private string $text;
    private array $logs;
    private ?string $logPath;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $text, array $logs, ?string $logPath)
    {
        $this->text = $text;
        $this->logs = $logs;
        $this->logPath = $logPath;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            to: new Address(config('mail.from.address'), config('mail.from.address')),
            subject: 'Report ' . Date::yesterday()->format('Y-m-d')
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
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
     * @return array
     */
    public function attachments()
    {
        if ($this->logPath === null) {
            return [];
        }
        return [
            Attachment::fromPath($this->logPath)
                ->as('laravel.log')
                ->withMime('plain/text')
        ];
    }
}
