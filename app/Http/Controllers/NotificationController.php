<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
class NotificationController extends Controller
{
    //
    public function index()
    {
       try {
        //code...
         // $notifications = Notification::where('user_id', 1)->get();
        // $notifications = Notification::where('user_id', 1)->get() ?? collect(); // Ensure $notifications is never null
        $notifications = Notification::all(); //
        
        // if ($notifications->isEmpty()) {
        //     \Log::info('No notifications found for user_id 1');
        // } else {
        //     \Log::info('Notifications retrieved: ' . $notifications->toJson());
        // }
    
      
        return response()->json($notifications, 200);
       } catch (\Throwable $th) {
        //throw $th;
        return response()->json(['message' => $th->getMessage()], 500);
       }
    }


    public function markAsRead($id)
    {
        $notification = Notification::find($id);
        if (!$notification) {
            return response()->json(['message' => 'Notification not found'], 404);
        }
        $notification->read = true;
        $notification->save();

        return response()->json(['message' => 'Notification marked as read']);
    }
}
