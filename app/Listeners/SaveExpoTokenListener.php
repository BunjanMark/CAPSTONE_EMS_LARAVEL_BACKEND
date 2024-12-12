<?php
// app/Listeners/SaveExpoTokenListener.php

namespace App\Listeners;

use App\Events\SendExpoTokenEvent;
use App\Models\ExpoToken;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SaveExpoTokenListener
// ?? just remove queuing and this works
{
    /**
     * Handle the event.
     *
     * @param SendExpoTokenEvent $event
     * @return void
     */
    public function handle(SendExpoTokenEvent $event)
    {
        \Log::info('Expo token event triggered for user: ' . $event->user->id);

        // Ensure that the Expo token is saved to the database
        ExpoToken::updateOrCreate(
            ['user_id' => $event->user->id], // Unique condition
            ['token' => $event->pushToken]   // Data to update or create
        );
    }
}