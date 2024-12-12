<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
 
use App\Models\User;
use App\Notifications\UpcomingEvents; // Import Vonage notification class
use App\Notifications\SendSmsWithTwilioNotification; // Import Twilio notification class
 

class NotificationUpcomingEventController extends Controller
{
    //
    public function sendReminder(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'user_id' => 'required|exists:users,id', // Ensure the user exists
            'message' => 'required|string|max:255', // Limit message length
        ]);

        // Find the user by ID
        $user = User::findOrFail($request->user_id);

        // Send the notification
        try {
            $user->notify(new UpcomingEvents($request->message));

            return response()->json([
                'success' => true,
                'message' => 'SMS notification sent successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send SMS notification.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    
}
