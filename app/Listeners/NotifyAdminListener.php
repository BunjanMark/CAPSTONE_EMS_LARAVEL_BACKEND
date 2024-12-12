<?php

namespace App\Listeners;

use App\Events\ServiceCreatedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use ExpoSDK\Expo;
use ExpoSDK\ExpoMessage;
use App\Models\ExpoToken;
use App\Models\Notification;

class NotifyAdminListener
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
    public function handle(ServiceCreatedEvent $event): void
    {
        try {
            $service = $event->service;
    
            // Fetch the admin's Expo token
            // Adjust this query to fetch the admin's Expo token by role, user_id, or another identifier
            // $adminExpoToken = ExpoToken::where('user_id', 1)->pluck('token')->first(); // Change 'role_id' as necessary
            $adminExpoToken = ExpoToken::where('user_id', 1)->pluck('token')->first(); // Change '++
    
            if (empty($adminExpoToken)) {
                \Log::info('No Expo token found for admin.');
                return;
            }
    
            // Create the notification message
            $message = (new ExpoMessage())
                ->setTitle('New Service Created')
                ->setBody("Service '{$service->serviceName}' has been added.")
                ->setData(['service_id' => $service->id])
                ->playSound();
    
            // Send the notification to the admin
            (new Expo)->send($message)->to([$adminExpoToken])->push();

                 // Store the notification in the database
                 Notification::create([
                    'title' => 'New Service Created',
                    'body' => "Service '{$service->serviceName}' has been added.",
                    'data' => json_encode(['service_id' => $service->id]),
                    'user_id' => 1, // The ID of the admin receiving the notification
                ]);

                
            \Log::info("Notification sent successfully to admin with token: " . $adminExpoToken);


   

 
        } catch (\Throwable $th) {
            \Log::error('Failed to send notification: ' . $th->getMessage());
        }
    }
    
}
