<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use App\Models\AccountRole; // Add this import
class EquipmentController extends Controller
{


    public function store(Request $request)
{
    $userId = Auth::id();
    $accountRoleId = $request->account_role_id;

    // Log incoming request data
    \Log::info('Incoming Request Data: ' . json_encode($request->json()->all()));

    // Log the details for debugging
    \Log::info('User ID: ' . $userId);
    \Log::info('Account Role ID from request: ' . $accountRoleId);

    // Query to check if the user has the required role
    $accountRole = AccountRole::where('user_id', $userId)
        ->where('role_id', 3) // you may modify this if necessary
        ->where('id', $accountRoleId)
        ->first();

    // Log the query result
    \Log::info('Account Role Query Result: ' . json_encode($accountRole));

    if (!$accountRole) {
        return response()->json(['message' => 'You do not have the required role to access this equipment'], 403);
    }

    // Validate the incoming data
    $validatedData = $request->validate([
        'item' => 'required|string',
        'number_of_items' => 'required|integer|min:1',
        'number_of_sort_items' => 'required|integer|min:0',
        'status' => 'nullable|string|in:Complete,Missing,Broken',
        'event_id' => 'required|exists:events,id',
    ]);

    // Attach the authenticated user's ID
    $validatedData['user_id'] = $userId;

    // Create a new equipment entry
    $equipment = Equipment::create($validatedData);

    return response()->json($equipment, 201);
}

    
    





    // Fetch all equipment or filter by event_id
    public function index(Request $request)
{
    $eventId = $request->query('event_id');

    if ($eventId) {
        $equipment = Equipment::where('event_id', $eventId)->get();
    } else {
        $equipment = Equipment::all();
    }

    return response()->json($equipment);
}
    
public function myEquipment(Request $request)
{
    $userId = Auth::id();
    $accountRole = AccountRole::where('user_id', $userId)->where('role_id', 3)->first();

    if (!$accountRole) {
        return response()->json(['message' => 'You do not have the required role to access this equipment'], 403);
    }

    $eventId = $request->query('event_id');

    if ($eventId) {
        $equipment = Equipment::where('user_id', $userId)
            // ->where('account_role_id', $accountRole->id)
            ->where('event_id', $eventId)
            ->get();
    } else {
        $equipment = Equipment::where('user_id', $userId)
            ->where('account_role_id', $accountRole->id)
            ->get();
    }

    return response()->json($equipment);
}



    public function getEquipmentForEvent($eventId)
    {
        $event = Event::find($eventId);

        if (!$event) {
            return response()->json(['error' => 'Event not found'], 404);
        }

        $equipment = Equipment::where('event_id', $eventId)->get();

        return response()->json($equipment);
    }
    public function getEquipmentForEventForUserId($eventId, $userId)
    {
        $event = Event::find($eventId);

        if (!$event) {
            return response()->json(['error' => 'Event not found'], 404);
        }

        $equipment = Equipment::where('event_id', $eventId)
            ->where('user_id', $userId)
            ->get();

        return response()->json($equipment);
    }
    public function myEquipments($eventId)
    {
        $equipments = Equipment::where('event_id', $eventId)
            ->where('user_id', 3)
            ->where('account_role_id', 3)
            ->get();
    
        return response()->json($equipments);
    }
    // Update an existing equipment entry
    public function update(Request $request, $id)
    {
        $equipment = Equipment::findOrFail($id);

        // Validate the incoming data
        $validatedData = $request->validate([
            'item' => 'sometimes|required|string',
            'number_of_items' => 'sometimes|required|integer',
            'number_of_sort_items' => 'sometimes|required|integer',
            'status' => 'nullable|string',
            'event_id' => 'sometimes|required|exists:events,id'
        ]);

        // Update the equipment entry
        $equipment->update($validatedData);

        return response()->json($equipment, 200);
    }

    // Delete an equipment entry
    public function destroy($id)
    {
        try {
            $equipment = Equipment::findOrFail($id);
            $equipment->delete();
            return response()->json(['message' => 'Item deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Item not found.'], 404);
        }
    }
}
