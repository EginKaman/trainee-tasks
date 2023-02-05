<?php declare(strict_types=1);

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

    private string $text;
    /**
     * @var array<string>
     */
    private array $logs;
    private ?string $logPath;

    /**
     * Create a new message instance.
     *
     * @param string $text
     * @param array<string> $logs
     * @param ?string $logPath
     */
    public function __construct(string $text, array $logs, ?string $logPath)
    {
        $this->text = $text;
        $this->logs = $logs;
        $this->logPath = $logPath;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address((string) config('mail.from.address'), (string) config('mail.from.address')),
            to: new Address((string) config('mail.from.address'), (string) config('mail.from.address')),
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
