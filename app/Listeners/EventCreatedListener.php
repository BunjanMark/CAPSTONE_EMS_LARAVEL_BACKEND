<?php

namespace App\Listeners;

use App\Events\EventCreatedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use ExpoSDK\Expo;
use ExpoSDK\ExpoMessage;
use App\Models\ExpoToken;
use App\Models\User;
use App\Models\Notification;
class EventCreatedListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(EventCreatedEvent $event): void
    {
        //
        try {
            
            $event = $event->event;

            $adminExpoToken = ExpoToken::where('user_id', 1)->pluck('token')->first(); // Change 'role_id' as necessary

            if (empty($adminExpoToken)) {
                \Log::info('No Expo token found for admin.');
                return;
            }
            $user = User::find($event->user_id);
            $name = $user->name;
            $message = (new ExpoMessage())
            ->setTitle('New Event Created')
            ->setBody("Event '{$event->name}' has been submitted by {$name}.")
            ->setData(['event_id' => $event->id])
            ->playSound();

        // Send the notification to the admin
        (new Expo)->send($message)->to([$adminExpoToken])->push();

               // Store the notification in the database
               Notification::create([
                'title' => 'New Event Created',
                'body' => "Event '{$event->name}' has been added. ",
                'data' => json_encode([
                    'event_id' => $event->id,
                    'name' => $event->name,
                    'type' => $event->type,
                ]),
                'user_id' => 1, // The ID of the admin receiving the notification
            ]);
        \Log::info("Notification sent successfully to admin with token: " . $adminExpoToken);
        } catch (\Throwable $th) {
            //throw $th;
            \Log::error('Failed to send notification: ' . $th->getMessage());
        }
    }
}
