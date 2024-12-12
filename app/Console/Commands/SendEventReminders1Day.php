<?php

namespace App\Console\Commands;


use Illuminate\Console\Command;
use App\Models\Event;
use App\Models\Guest;
use App\Mail\EventReminder1Day;
use Illuminate\Support\Facades\Mail;

class SendEventReminders1Day extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
   
     protected $signature = 'event:send-reminders-1-day';
     protected $description = 'Send event reminders to guests for events within 24 hours';
 
     public function __construct()
     {
         parent::__construct();
     }
 
    /**
     * The console command description.
     *
     * @var string
     */
    public function handle()
    {
        try {
            // Calculate the date 3 days from today
            $reminderDate = now()->addDays(1)->format('Y-m-d');

            // Fetch all events scheduled for 3 days from today
            $events = Event::where('date', $reminderDate)->get();

            if ($events->isEmpty()) {
                $this->info("No events scheduled for {$reminderDate}.");
                return;
            }

            foreach ($events as $event) {
                // Fetch guests associated with the event
                $guests = Guest::where('event_id', $event->id)->get();

                if ($guests->isEmpty()) {
                    $this->info("No guests found for event ID {$event->id} ({$event->name}).");
                    continue;
                }

                // Send reminders to each guest
                foreach ($guests as $guest) {
                    Mail::to($guest->email)->send(new EventReminder1Day($event, $guests));
                    $this->info("Reminder sent to {$guest->GuestName} ({$guest->email}).");
                }
            }

            $this->info('All event reminders processed successfully.');
            \Log::info('Sending event reminders...');

        } catch (\Throwable $e) {
            \Log::error('Error sending event reminders: ' . $e->getMessage());
            $this->error('An error occurred: ' . $e->getMessage());
        }
    }
}
