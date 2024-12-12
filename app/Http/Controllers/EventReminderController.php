<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Guest;
use App\Mail\EventReminder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EventReminderController extends Controller
{
    /**
     * Send reminder to all guests related to the event.
     *
     * @param int $eventId
     * @return \Illuminate\Http\Response
     */
    public function sendEventReminder($eventId)
    {
        // Retrieve the event with the related guests
        $event = Event::with('guests')->find($eventId);

        // Check if event exists
        if (!$event) {
            return response()->json(['message' => 'Event not found.'], 404);
        }

        // Get all guests associated with the event
        $guests = $event->guests;

        // Loop through each guest and send them the event reminder email
        foreach ($guests as $guest) {
            Mail::to($guest->email)->send(new EventReminder($event, $guests));
        }

        return response()->json(['message' => 'Event reminders sent to all guests.']);
    }
}
