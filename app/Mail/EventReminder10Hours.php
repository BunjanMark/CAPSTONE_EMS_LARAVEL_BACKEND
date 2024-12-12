<?php

namespace App\Mail;

use App\Models\Event;
use App\Models\Guest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EventReminder10Hours extends Mailable
{
    use Queueable, SerializesModels;

    public $event;
    public $guests;

    /**
     * Create a new message instance.
     *
     * @param Event $event
     * @param \Illuminate\Database\Eloquent\Collection $guests
     * @return void
     */
    public function __construct(Event $event, $guests)
    {
        $this->event = $event;
        $this->guests = $guests;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.event_reminder_10_hours')
        ->subject("Get Ready for your event {$this->event->name} " . ltrim(\Carbon\Carbon::parse($this->event->time)->format('h'), '0') . " hours from now!")
        ->with([
                        'eventName' => $this->event->name,
                        'eventType' => $this->event->type,
                        'eventPax' => $this->event->pax,
                        'eventStatus' => $this->event->status,
                        'eventLocation' => $this->event->location,
                        'eventDescription' => $this->event->description,
                        'eventCoverPhoto' => $this->event->coverPhoto,
                        'eventDate' => $this->event->date,
                        'eventTime' => $this->event->time,
                    ]);
    }
}
