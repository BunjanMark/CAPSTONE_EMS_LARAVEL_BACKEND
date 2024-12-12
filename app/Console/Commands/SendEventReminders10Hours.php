<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Event;
use App\Models\Guest;
use App\Mail\EventReminder10Hours;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SendEventReminders10Hours extends Command
{
    protected $signature = 'event:send-reminders-10-hours';
    protected $description = 'Send event reminders to guests for events within 10 hours';

    public function handle()
    {
        try {
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $tenHoursLater = Carbon::now()->addHours(10)->format('Y-m-d H:i:s');

            $this->info('Now: ' . $now);
            $this->info('Ten Hours Later: ' . $tenHoursLater);

            $events = Event::where(function ($query) use ($now, $tenHoursLater) {
                $query->whereRaw('CONCAT(date, " ", time) >= ?', [$now])
                      ->whereRaw('CONCAT(date, " ", time) <= ?', [$tenHoursLater]);
            })->get();


            if ($events->isEmpty()) {
                $this->info("No events scheduled within the next 10 hours.");
            } else {
                foreach ($events as $event) {
                    // Get guests for the current event
                    $guests = Guest::where('event_id', $event->id)->get();

                    if ($guests->isEmpty()) {
                        $this->info("No guests found for event ID {$event->id} ({$event->name}).");
                        continue;
                    }

                    // Send reminder emails to each guest
                    foreach ($guests as $guest) {
                        Mail::to($guest->email)->send(new EventReminder10Hours($event, $guests));
                        $this->info("Reminder sent to {$guest->GuestName} ({$guest->email}).");
                    }
                }
            }

            $this->info('All event reminders processed successfully.');
        } catch (\Throwable $e) {
            \Log::error('Error sending event reminders: ' . $e->getMessage());
            $this->error('An error occurred: ' . $e->getMessage());
        }
    }
}
