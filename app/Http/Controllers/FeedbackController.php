<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Models\Event;
use App\Models\Guest;
use App\Jobs\FetchEventServicesJob;
use Illuminate\Support\Facades\Cache;

class FeedbackController extends Controller
{
    /**
     * Submit feedback to the Flask API.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */


     public function showForm(Request $request)
     {
         $eventId = $request->query('event_id');
         $guestId = $request->query('guest_id');
     
         // Retrieve the event
         $event = Event::findOrFail($eventId);
     
         // Retrieve services associated with the event, using caching for efficiency
         $services = Cache::remember("event_services_{$eventId}", 60, function () use ($eventId) {
             return DB::table('services')
                 ->join('event_services_providers', 'services.id', '=', 'event_services_providers.service_id')
                 ->where('event_services_providers.event_id', $eventId)
                 ->select('services.serviceCategory') // Adjust column name based on your database schema
                 ->get()
                 ->pluck('serviceCategory')
                 ->toArray(); // Get an array of service names
         });
     
         // Pass data to the view
         return view('feedback.form', compact('eventId', 'guestId', 'event', 'services'));
     }
     
     
     public function submitFeedback(Request $request)
     {
         // Validate all feedback aspects and related fields
         $validatedData = $request->validate([

             'catering_feedback' => 'nullable|string',
             'decoration_feedback' => 'nullable|string',
             'food_catering_feedback' => 'nullable|string',
             'accommodation_feedback' => 'nullable|string',
             'transportation_feedback' => 'nullable|string',
             'photography_feedback' => 'nullable|string',
             'videography_feedback' => 'nullable|string',
             'host_feedback' => 'nullable|string',
             'entertainment_feedback' => 'nullable|string',
             'sound_feedback' => 'nullable|string',
             'lighting_feedback' => 'nullable|string',
             'venue_management_feedback' => 'nullable|string',
             'marketing_feedback' => 'nullable|string',
             'other_feedback' => 'nullable|string',
             'event_id' => 'required|integer|exists:events,id',
             'guest_id' => 'required|integer|exists:guest,id', // Corrected table name to match 'guests'
         ]);
     
         // Log validated feedback for debugging or storage
         \Log::info('Feedback received:', $validatedData);
     
         // Redirect back with a success message
         return redirect()->route('feedback.form')->with('message', 'Thank you for your feedback!');
     }



}
