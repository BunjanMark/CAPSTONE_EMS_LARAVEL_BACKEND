<?php

namespace App\Listeners;

use App\Events\PackageCreatedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use ExpoSDK\Expo;
use ExpoSDK\ExpoMessage;
use App\Models\ExpoToken;
use App\Models\User;
use App\Notifications\PackageCreatedNotification;
use App\Models\Package;
use App\Models\Notification;
class PackageCreatedListener
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
    public function handle(PackageCreatedEvent $event): void
    {
        //
        try {
            $package = $event->package;

            $adminExpoToken = ExpoToken::where('user_id', 1)->pluck('token')->first();
            if (empty($adminExpoToken)) {
                \Log::info('No Expo token found for admin.');
                return;
            }
                // Create the notification message
                $message = (new ExpoMessage())
                ->setTitle('New Package Created')
                ->setBody("Package '{$package->packageName}' has been added. Price: {$package->totalPrice}")
                ->setData(['package_id' => $package->id])
                ->playSound();
    
            // Send the notification to the admin
            (new Expo)->send($message)->to([$adminExpoToken])->push();

             // Store the notification in the database
             Notification::create([
                'title' => 'New Package Created',
                'body' => "Package '{$service->packageName}' has been added. ",

                'data' => json_encode([
                    'package_id' => $package->id,
                    'package_name' => $package->packageName,
                    'eventType' => $package->eventType,
                    'packageType' => $package->packageType,
                    'totalPrice' => $package->totalPrice,
                    'coverPhoto' => $package->coverPhoto,
                    'services' => json_decode($package->services),
                ]),
                'user_id' => 1, // The ID of the admin receiving the notification
            ]);

            
            \Log::info("Notification sent successfully to admin with token: " . $adminExpoToken);
            $notification = new PackageCreatedNotification($event->package);
            $event->user->notify($notification);
        } catch (\Throwable $th) {
            //throw $th;
        }

        // sending notification to many channels
//         $channels = ['expo', 'email', 'sms'];
// foreach ($channels as $channel) {
//     switch ($channel) {
//         case 'expo':
//             $message = (new ExpoMessage())
//                 ->setTitle('New Package Created')
//                 ->setBody("Package '{$package->packageName}' has been added.")
//                 ->setData(['package_id' => $package->id])
//                 ->playSound();
//             (new Expo)->send($message)->to([$adminExpoToken])->push();
//             break;
//         case 'email':
//             Mail::to($admin->email)->send(new PackageCreatedEmail($package));
//             break;
//         case 'sms':
            // Send SMS notification using a third-party service
//             break;
//     }
// }
    }
}
