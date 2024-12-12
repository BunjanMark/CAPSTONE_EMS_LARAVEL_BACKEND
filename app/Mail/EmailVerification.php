<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EmailVerification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $verificationCode;

    public function __construct($verificationCode)
    {
        $this->verificationCode = $verificationCode;
    }

    // Build the message with subject and the view
    public function build()
    {
        return $this->subject('Your Email Verification Code')
                    ->view('emails.verification')  // Use correct view name here
                    ->with(['code' => $this->verificationCode]);
    }

    // Optionally you can add an envelope for subject
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Email Verification',
        );
    }

    // If you're not using this method, you can remove it
    public function content(): Content
    {
        return new Content(
            view: 'emails.verification',  // Make sure this view exists
        );
    }

    // Attachments can be added here if needed
    public function attachments(): array
    {
        return [];
    }
}
