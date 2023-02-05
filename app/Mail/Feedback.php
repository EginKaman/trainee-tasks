<?php declare(strict_types=1);

namespace App\Mail;

use App\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\{Address, Attachment, Content, Envelope};
use Illuminate\Queue\SerializesModels;
use Sichikawa\LaravelSendgridDriver\SendGrid;

class Feedback extends Mailable implements ShouldQueue
{
    use Queueable;
    use SendGrid;
    use SerializesModels;

    private Message $message;

    /**
     * Create a new message instance.
     */
    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address((string) config('mail.from.address'), (string) config('mail.from.name')),
            to: new Address($this->message->email, $this->message->email),
            subject: 'Feedback'
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.feedback',
            with: [
                'id' => $this->message->id,
                'text' => $this->message->text,
                'email' => $this->message->email,
                'method' => $this->message->method,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
