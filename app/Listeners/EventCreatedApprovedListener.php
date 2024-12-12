<?php

namespace App\Listeners;

use App\Events\EventCreatedApprovedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use ExpoSDK\Expo;
use ExpoSDK\ExpoMessage;
use App\Models\ExpoToken;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Support\Facades\DB;
class EventCreatedApprovedListener
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
    // This will notify the user who booked the event 
    // public function handle(EventCreatedApprovedEvent $event): void
    // {
    //     try {
    //         $event = $event->event;

    //         // Get the user who booked the event
    //         $user = User::find($event->user_id);

    //         if (!$user) {
    //             \Log::info('No user found for event ID: ' . $event->id);
    //             return;
    //         }

    //         // Fetch the Expo token for the user
    //         $userExpoToken = ExpoToken::where('user_id', $user->id)->pluck('token')->first();

    //         if (empty($userExpoToken)) {
    //             \Log::info('No Expo token found for user ID: ' . $user->id);
    //             return;
    //         }

    //         // Prepare the message
    //         $message = (new ExpoMessage())
    //             ->setTitle('Event Approved')
    //             ->setBody("Your event '{$event->name}' has been approved and is now scheduled.")
    //             ->setData(['event_id' => $event->id])
    //             ->playSound();

    //         // Send the notification to the user
    //         (new Expo)->send($message)->to([$userExpoToken])->push();

    //         // Store the notification in the database
    //         Notification::create([
    //             'title' => 'Event Approved',
    //             'body' => "Your event '{$event->name}' has been approved.",
    //             'data' => json_encode([
    //                 'event_id' => $event->id,
    //                 'name' => $event->name,
    //                 'type' => $event->type,
    //             ]),
    //             'user_id' => $user->id, // The ID of the user receiving the notification
    //         ]);

    //         \Log::info("Notification sent successfully to user with token: " . $userExpoToken);
    //     } catch (\Throwable $th) {
    //         \Log::error('Failed to send notification: ' . $th->getMessage());
    //     }
    // }

    // This will notify the user and the service providers in the event
    public function handle(EventCreatedApprovedEvent $event): void
    {
        try {
            $event = $event->event;
    
            // Get the user who booked the event
            $user = User::find($event->user_id);
    
            if (!$user) {
                \Log::info('No user found for event ID: ' . $event->id);
                return;
            }
    
            // Notify the user who booked the event
            $userExpoToken = ExpoToken::where('user_id', $user->id)->pluck('token')->first();
            if (!empty($userExpoToken)) {
                $message = (new ExpoMessage())
                    ->setTitle('Event Approved')
                    ->setBody("Your event '{$event->name}' has been approved and is now scheduled.")
                    ->setData(['event_id' => $event->id])
                    ->playSound();
                (new Expo)->send($message)->to([$userExpoToken])->push();
    
                Notification::create([
                    'title' => 'Event Approved',
                    'body' => "Your event '{$event->name}' has been approved.",
                    'data' => json_encode([
                        'event_id' => $event->id,
                        'name' => $event->name,
                        'type' => $event->type,
                    ]),
                    'user_id' => $user->id,
                ]);
    
                \Log::info("Notification sent successfully to user with token: " . $userExpoToken);
            } else {
                \Log::info('No Expo token found for user ID: ' . $user->id);
            }
    
            // Notify the service providers
            $serviceProviders = DB::table('event_services_providers')
            ->where('event_id', $event->id)
            ->select('user_id', 'package_id') // Explicitly select necessary columns
            ->distinct() // Ensures unique rows based on the selected columns
            ->get();
            foreach ($serviceProviders as $serviceProvider) {
                $providerUser = User::find($serviceProvider->user_id);
                if (!$providerUser) {
                    \Log::info('No user found for service provider ID: ' . $serviceProvider->user_id);
                    continue;
                }
    
                // Fetch the Expo token for the service provider
                $providerExpoToken = ExpoToken::where('user_id', $providerUser->id)->pluck('token')->first();
                if (empty($providerExpoToken)) {
                    \Log::info('No Expo token found for service provider ID: ' . $providerUser->id);
                    continue;
                }
    
                // Prepare and send the notification
                $providerMessage = (new ExpoMessage())
                    ->setTitle('New Event Assignment')
                    ->setBody("You have been assigned to event '{$event->name}' as part of the service package.")
                    ->setData(['event_id' => $event->id, 'package_id' => $serviceProvider->package_id])
                    ->playSound();
    
                (new Expo)->send($providerMessage)->to([$providerExpoToken])->push();
    
                // Store the notification in the database
                Notification::create([
                    'title' => 'New Event Assignment',
                    'body' => "You have been assigned to event '{$event->name}' as part of the service package.",
                    'data' => json_encode([
                        'event_id' => $event->id,
                        'package_id' => $serviceProvider->package_id,
                    ]),
                    'user_id' => $providerUser->id,
                ]);
    
                \Log::info("Notification sent successfully to service provider with token: " . $providerExpoToken);
            }
        } catch (\Throwable $th) {
            \Log::error('Failed to send notifications: ' . $th->getMessage());
        }
    }
    

    
}
