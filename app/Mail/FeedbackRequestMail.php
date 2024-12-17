<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FeedbackRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $event;
    public $guest;
    public function __construct($event, $guest)
    {
        $this->event = $event;
        $this->guest = $guest;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Feedback Request Mail',
        );
    }

    /**
     * Get the message content definition.
     */

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
    public function build()
    {
        return $this->subject('We value your feedback!')
                    ->view('emails.feedback-request')
                    ->with([
                        'eventName' => $this->event->name,
                        'guestName' => $this->guest->GuestName,
                        'guest' => $this->guest,
                        'event' => $this->event,
                        'feedbackLink' => url("/feedback/form?event_id={$this->event->id}&guest_id={$this->guest->id}&guest_name={$this->guest->GuestName}"),
                    ]); // .ngrok
    }
    
}
