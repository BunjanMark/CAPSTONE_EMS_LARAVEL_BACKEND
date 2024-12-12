<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Guest;
use App\Models\Event;  // Ensure the Event model is included
use Illuminate\Support\Facades\DB;

class GuestController extends Controller
{
    
    // Fetch all guest for a specific event
    public function getGuestByEvent($eventid)
    {
        try {
            // Fetch guest for a specific event
            $guest = Guest::where('event_id', $eventid)->get();
            
            // If no guest are found, return a 404 response
            if ($guest->isEmpty()) {
                return response()->json(['message' => 'No guest found for this event'], 404);
            }

            return response()->json($guest, 200);  // Return guest data with 200 OK status
        } catch (\Throwable $th) {
            // Log the error
            \Log::error('Error fetching guest for event: ' . $th->getMessage());
            return response()->json(['message' => 'Error fetching guest: ' . $th->getMessage()], 500);
        }
    }

    // Store a new guest
   public function store(Request $request)
{ 
    // Validate the request data for multiple guests
    $request->validate([
        'guest' => 'required|array',
        'guest.*.GuestName' => 'required|string|max:255',
        'guest.*.email' => 'required|email|max:255',
        'guest.*.phone' => 'required|string|max:15',
        'guest.*.role' => 'required|string|max:255',
        'eventId' => 'required|exists:events,id', // Ensure event exists
    ]);

    // Retrieve the event ID
    $eventId = $request->input('eventId');

    // Process each guest
    $guests = [];
    foreach ($request->input('guest') as $guestData) {
        $guest = new Guest();
        $guest->GuestName = $guestData['GuestName'];
        $guest->email = $guestData['email'];
        $guest->phone = $guestData['phone'];
        $guest->event_id = $eventId;
        $guest->role = $guestData['role'];
        $guest->save();

        $guests[] = $guest; // Add saved guest to the response array
    }

    // Return all saved guests with a 201 Created status
    return response()->json($guests, 201);
}



    // Edit an existing guest
   // Inside GuestController

   public function update(Request $request, $id)
{
    $request->validate([
        'GuestName' => 'sometimes|required|string|max:255',
        'email' => 'sometimes|required|email|max:255',
        'phone' => 'sometimes|required|string|max:15',
        'role' => 'sometimes|required|string',
        'status' => 'sometimes|required|in:Present,Absent',
    ]);

    // Find or fail
    $guest = Guest::findOrFail($id);

    // Update guest data
    $guest->update($request->only(['GuestName', 'email', 'phone', 'role', 'status']));

    return response()->json($guest, 200);
}


   


    // Delete a guest
    public function destroy($id)
    {
        $guest = Guest::find($id);

        if (!$guest) {
            return response()->json(['message' => 'Guest not found'], 404);
        }

        $guest->delete();

        return response()->json(['message' => 'Guest deleted successfully'], 200);
    }
}
