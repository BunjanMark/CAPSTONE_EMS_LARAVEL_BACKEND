<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EmailNotice extends Mailable
{
    use Queueable, SerializesModels;

    public $eventName;
    public $eventDate;
    /**
     * Create a new message instance.
     */
    public function __construct($eventName, $eventDate)
    {
        $this->eventName = $eventName;
        $this->eventDate = $eventDate;
    }


    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Email Notice',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'view.name',
        );
    }

    public function build()
    {
        // Plain-text content for the email
        $message = <<<EOT
Hello,

This is a reminder about your upcoming event: "{$this->eventName}".

Event Date: {$this->eventDate}

We look forward to seeing you there!

Best regards,
Your App Team
EOT;

        return $this->subject('Upcoming Event Reminder')
                    ->text('emails.raw')
                    ->with(['rawMessage' => $message]);
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
