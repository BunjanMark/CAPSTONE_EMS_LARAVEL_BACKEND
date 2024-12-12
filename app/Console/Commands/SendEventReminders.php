<?php

namespace App\Console\Commands;


use Illuminate\Console\Command;
use App\Models\Event;
use App\Models\Guest;
use App\Mail\EventReminder;
use Illuminate\Support\Facades\Mail;

class SendEventReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
   
     protected $signature = 'event:send-reminders';
     protected $description = 'Send event reminders to guests for upcoming events';
 
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
            // Calculate the date 7 days from today
            $reminderDate = now()->addDays(6)->format('Y-m-d');
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
                    Mail::to($guest->email)->send(new EventReminder($event, $guests));
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
    // This will fire to the specified date
    // public function handle()
    // {
    //     try {
    //         // Fetch all upcoming events for today
    //         $testDate = '2024-12-25'; // Replace with your desired date

    //         // Fetch events for the test date
    //         $events = Event::where('date', $testDate)->get();
    
    //         if ($events->isEmpty()) {
    //             $this->info("No events scheduled for the date {$testDate}.");
    //             return;
    //         }

    //         foreach ($events as $event) {
    //             // Fetch guests associated with the event
    //             $guests = Guest::where('event_id', $event->id)->get();

    //             if ($guests->isEmpty()) {
    //                 $this->info("No guests found for event ID {$event->id} ({$event->name}).");
    //                 continue;
    //             }

    //             // Send reminders to each guest
    //             foreach ($guests as $guest) {
    //                 Mail::to($guest->email)->send(new EventReminder($event, $guests));
    //                 $this->info("Reminder sent to {$guest->GuestName} ({$guest->email}).");
    //             }
    //         }

    //         $this->info('All event reminders processed successfully.');
    //         \Log::info('Sending event reminderss...');
    //         // Your logic to send reminders
        

    //     } catch (\Throwable $e) {
    //         \Log::error('Error sending event reminders: ' . $e->getMessage());
    //         $this->error('An error occurred: ' . $e->getMessage());
    //     }
    // }

    /**
     * Execute the console command.
     */
 
}
