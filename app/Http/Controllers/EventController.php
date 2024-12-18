<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Guest;
use App\Models\Equipment;
use App\Models\Package;
use App\Models\Service;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use App\Events\EventCreatedEvent;
use App\Models\AccountRole;
use App\Notifications\EventScheduleNotice;
use App\Events\EventCreatedApprovedEvent;
use Carbon\Carbon;
use App\Mail\FeedbackRequestMail;
use Illuminate\Support\Facades\Mail;
class EventController extends Controller
{
    //Add a method to fetch all events
    public function index()
{
    $events = Event::withCount('guest')->with('user')->get();
    return response()->json($events);
}

public function eventsByMonth($month)
{
    $events = Event::whereMonth('date', $month)->get();
    return response()->json($events);
}


 public function eventsForDay($date)
{
    $events = Event::whereDate('date', $date)->get();
    return response()->json($events);
}

public function store(Request $request)
{
    try {
        // Ensure user is authenticated
        $user = Auth::user();
        // return response()->json($user, 200);
        if (!$user) {   
            return response()->json(['message' => 'Unauthorized. Please log in.'], 401);
        }

        // Validate the incoming request
        $validatedData = $request->validate([
            'eventName' => 'required|string|max:255',
            'eventType' => 'required|string',
            'eventPax' => 'required|numeric|min:1',
            'eventDate' => 'required|date',
            'eventTime' => 'required|date_format:H:i',
            'eventStatus' => 'required|string', // e.g., Tentative, Booked, etc.
            'eventLocation' => 'required|string',
            'description' => 'required|string',
            'coverPhoto' => 'nullable|string', 
            'totalPrice' => 'nullable|numeric|min:1',
            // 'package_id' => 'required|exists:packages,id',
            'packages' => 'nullable|array',
            'guest' => 'nullable|array',
            'guest.*.GuestName' => 'nullable|string|max:255',
            'guest.*.email' => 'nullable|email',
            'guest.*.role' => 'nullable|string|max:255',
            'guest.*.phone' => 'nullable|string|max:15',
        ]);

        // Attach user ID to validated data
        $validatedData['user_id'] = $user->id;
        
        // Ensure that 'packages' is set to an empty array if not provided
        if (!isset($validatedData['packages'])) {
            $validatedData['packages'] = []; // Default to an empty array if services are not provided
        }

        // Create the event with package association
        $event = Event::create([
            'name' => $validatedData['eventName'],
            'type' => $validatedData['eventType'],
            'pax' => $validatedData['eventPax'],
            'date' => $validatedData['eventDate'],
            'time' => $validatedData['eventTime'],
            'totalPrice' => $validatedData['totalPrice'],
            'status' => $validatedData['eventStatus'],
            'location' => $validatedData['eventLocation'],
            'description' => $validatedData['description'],
            'coverPhoto' => $validatedData['coverPhoto'],
            // 'package_id' => $validatedData['package_id'],
            'packages' => json_encode($validatedData['packages']), // Store packages as JSON
            'user_id' => $validatedData['user_id'], // Now user_id is explicitly set
        ]);
        // In your Event model or controller when creating or updating an event
        $event->event_datetime = Carbon::parse($event->date . ' ' . $event->time);
        $event->save();
        event(new EventCreatedEvent($event));

        if (isset($validatedData['packages']) && count($validatedData['packages']) > 0) {
            foreach ($validatedData['packages'] as $packageId) {
                DB::table('event_packages')->insert([
                    'event_id' => $event->id,
                    'package_id' => $packageId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
        // Create equipment records for the event
        // foreach ($validatedData['packages'] as $packageId) {
        //     $package = Package::find($packageId);
        //     $services = json_decode($package->services, true);
        
        //     foreach ($services as $serviceId) {
        //         $service = Service::find($serviceId);
        //         $equipment = Equipment::create([
        //             'event_id' => $event->id,
        //             'service_id' => $serviceId,
        //             'user_id' => $service->user_id,
        //         ]);
        
        //         // Fetch the AccountRole and User data
        //         $AccountRoledata = AccountRole::where('user_id', $service->user_id)
        //                                       ->where('role_id', 3) // Ensure the role_id is 3
        //                                       ->first();
                
        //         if ($AccountRoledata) {
        //             $equipment->account_role_id = $AccountRoledata->id;
        //         }
        
        //         $equipment->save();
        //     }
        // }
        // foreach ($validatedData['packages'] as $packageId) {
        //     $package = Package::find($packageId);
        //     $services = json_decode($package->services, true);
        
        //     foreach ($services as $serviceId) {
        //         $service = Service::find($serviceId);
        //         $AccountRoledata = AccountRole::where('user_id', $service->user_id)->first();
        
        //         $equipment = Equipment::create([
        //             'event_id' => $event->id,
        //             'service_id' => $serviceId,
        //             'user_id' => $service->user_id,
        //             'account_role_id' => $AccountRoledata ? $AccountRoledata->id : null,
        //         ]);
        //     }
        // }

       // Add guest to the event
       $guests = [];
       foreach ($validatedData['guest'] as $guestData) {
           $guest = Guest::create([
               'event_id' => $event->id,
               'GuestName' => $guestData['GuestName'],
               'email' => $guestData['email'],
               'phone' => $guestData['phone'],
               'role' => $guestData['role'],


           ]);
           $guests[] = $guest;
       }

        // add service providers included in the package to the guest table
        foreach ($validatedData['packages'] as $packageId) {
            $package = Package::find($packageId);
            $services = json_decode($package->services, true);
            $serviceProviders = [];
            foreach ($services as $service){
                $serviceProviderData = Service::find($service);
                $serviceProviders[] = [
                    'user_id' => $serviceProviderData->user_id,
                    // 'id' => $serviceProviderData->id,
                    // 'serviceName' => $serviceProviderData->serviceName,
                    // 'serviceFeatures' => $serviceProviderData->serviceFeatures,
                    // 'verified' => $serviceProviderData->verified,
                    // 'basePrice' => $serviceProviderData->basePrice,
                    // 'pax' => $serviceProviderData->pax,
                    // 'servicePhotoURL' => $serviceProviderData->servicePhotoURL,
                    // 'serviceCategory' => $serviceProviderData->serviceCategory,
                    // 'availability_status' => $serviceProviderData->availability_status,
                ];
               
            }

            foreach ($serviceProviders as $serviceProvider) {
                // Fetch the AccountRole and User data
                $AccountRoledata = AccountRole::find($serviceProvider['user_id']);
                $UserData = User::find($serviceProvider['user_id']);
            
                // Determine the role name based on role_id
                $roleName = match ($UserData->role_id) {
                    3 => 'Service Provider',
                    2 => 'customer', // Example: handle other roles
                    1 => 'admin', // Example: handle other roles
                    default => 'N/A', // Default role name if not matched
                };
            
                // Create the guest record
                Guest::create([
                    'event_id' => $event->id,
                    'GuestName' => $UserData->name,
                    'email' => $UserData->email,
                    'phone' => $UserData->phone_number,
                    'role' => $roleName,
                ]);
            }
            
            
        }

        foreach ($validatedData['packages'] as $packageId) {
            $package = Package::find($packageId);
            $services = json_decode($package->services, true);
        
            foreach ($services as $serviceId) {
                $service = Service::find($serviceId);
                $serviceProvider = User::find($service->user_id);
        
                // Insert into event_services_providers table
                DB::table('event_services_providers')->insert([
                    'event_id' => $event->id,
                    'package_id' => $packageId,
                    'service_id' => $serviceId,
                    'user_id' => $serviceProvider->id,
                    'service_provider_name' => $serviceProvider->name,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
        // Return the created event along with its associated package and user
        return response()->json([$event->load('package'), $user,  'guests' => Guest::where('event_id', $event->id)->get(),  "my services" => $serviceProviders], 201); // Include package in response
    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Validation failed.',
            'errors' => $e->errors(),
        ], 422);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
        ], 500);
    }
}


public function fetchEventsByType(Request $request)
{
    try {
        $eventType = $request->input('type'); // Get the 'eventType' parameter from the request

        if (!$eventType) {
            return response()->json(['error' => 'Event type is required'], 400); // Return error if eventType is not provided
        }

        // Fetch events that match the provided event type
        $events = Event::where('type', $eventType)->get();

        if ($events->isEmpty()) {
            return response()->json(['error' => 'No events found for this type'], 404); // Return error if no events are found
        }

        return response()->json($events); // Return the filtered events as a JSON response

    } catch (\Exception $e) {
        \Log::error('Error fetching events by type: ' . $e->getMessage());
        return response()->json(['error' => 'Failed to fetch events'], 500); // Return error if something goes wrong
    }
}


public function getEventsWithMyServices1(Request $request)
{
    $userId = Auth::id();
    $myServices = Service::where('user_id', $userId)->pluck('id');
    $packages = Package::whereHas('services', function ($query) use ($myServices) {
        $query->whereIn('service_id', $myServices);
    })->pluck('id');
    $events = Event::whereHas('packages', function ($query) use ($packages) {
        $query->whereIn('package_id', $packages);
    })->get();

    return response()->json($events);
}

public function getEventServices(Request $request, $eventId)
{
    $eventServices = DB::table('event_services_providers')->where('event_id', $eventId)->pluck('service_id');
    $services = Service::whereIn('id', $eventServices)->get();

    return response()->json($services);
}

public function fetchEventsByDate($date)
{
        try {
                // Get the count of events on a specific date
                $eventsCount = Event::whereDate('date', $date)
                ->selectRaw('count(*) as count')
                        ->first();

                    if ($eventsCount->count >= 3) {
                        return response()->json(['count' => $eventsCount->count]);
                    } else {
                        return response()->json(['count' => 0]);
                    }
                } catch (\Throwable $th) {
                    //throw $th;
                    return response()->json(['message' => $th->getMessage()], 500);
                } catch (\Exception $e) {
                    return response()->json([
                        'status' => 'error',
                        'message' => $e->getMessage(),
                    ], 500);
         }
}
 
public function showEventById($eventId)
{
    try {
        $event = Event::with('guest')->find($eventId);

        if (!$event) {
            return response()->json(['error' => 'Event not found'], 404);
        }

        return response()->json($event);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'An error occurred while fetching the event',
        ], 500);
    }
}

    public function getEventsByType($type)
{
    $events = Event::where('type', $type)->get();
    return response()->json($events);
}
public function updateEvent(Request $request, $eventId)
{
    DB::beginTransaction();

    try {
        $event = Event::find($eventId);

        if (!$event) {
            return response()->json(['error' => 'Event not found'], 404);
        }

        // Define the fields you want to update
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'time' => 'required|date_format:H:i:s',
            'location' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|string',
            'pax' => 'required|integer|min:1',
            'totalPrice' => 'required|numeric|min:0',
            'type' => 'required|string',
            'packages' => 'nullable|string', // Ensure this matches your frontend input
            'coverPhoto' => 'nullable|string', // Optional, can be null
            'payment_status' => 'required|in:Downpayment,Paid',  

        ]);

        // Update the event with the validated data
        $event->update($validatedData);

        DB::commit();

        return response()->json(['message' => 'Event updated successfully'], 200);

    } catch (\Illuminate\Validation\ValidationException $e) {
        DB::rollBack();
        return response()->json([
            'status' => 'error',
            'message' => 'Validation failed.',
            'errors' => $e->errors(),
        ], 422);
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
        ], 500);
    }
}

    // public function updateEvent(Request $request, $eventId)
    // {
    //     DB::beginTransaction();

    //     try {
    //         $event = Event::find($eventId);

    //         if (!$event) {
    //             return response()->json(['error' => 'Event not found'], 404);
    //         }

    //         $validatedData = $request->validate([
    //             'status' => 'required|string',
    //         ]);

    //         $event->update($validatedData);

    //         DB::commit();

    //         return response()->json(['message' => 'Event status updated successfully'], 200);

    //     } catch (\Illuminate\Validation\ValidationException $e) {
    //         DB::rollBack();
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'Validation failed.',
    //             'errors' => $e->errors(),
    //         ], 422);
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => $e->getMessage(),
    //         ], 500);
    //     }
    // }
    public function archiveEvent($eventId)
    {
        DB::beginTransaction();

        try {
            $event = Event::find($eventId);

            if (!$event) {
                return response()->json(['error' => 'Event not found'], 404);
            }

            // Set the event as archived
            $event->archived = true;
            $event->save();

            DB::commit();

            return response()->json(['message' => 'Event archived successfully'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
// Fetch all active events
    public function getActiveEvents()
    {
        $events = Event::where('archived', false)->get();
        return response()->json($events);
    }

    // Fetch all archived events
    public function getArchivedEvents()
    {
        $events = Event::where('archived', true)->get();
        return response()->json($events);
    }

    //function to softdelete event
    public function deleteEvent($id)
    {
        try {
            $event = Event::findOrFail($id);

            // perform soft dekeying
            $event->delete(); 
            return response()->json([
                'success' => true,
                'message' => 'Event Deleted Successfully',  //
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['message' => $th->getMessage()], 500);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Event not found or could not be deleted',
                'error' => $e->getMessage(),
            ], 404);
        }
    }

    // delete
    // Method to retrieve deleted events
    public function restoreEvent($id)
    {
        try {
            $event = Event::withTrashed()->findOrFail($id);

            $event->restore();

            return response()->json([
                'success' => true,
                'message' => 'Event restored successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Event not found or could not be restored',
                'error' => $e->getMessage(),
            ], 404);
        }
    }

    public function getEvents(Request $request)
{
    try {
        $events = Event::where('user_id', $userId)->get(); // Use $userId directly
        return response()->json($events, 200);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Failed to fetch events'], 500);
    }
}


public function getServiceProviderInfoByUserId($eventId, $userId)
{
    try {
        $event = Event::where('id', $eventId)->where('user_id', $userId)->first();
        if (!$event) {
            return response()->json(['error' => 'Event not found'], 404);
        }

        $accountRoles = AccountRole::where('user_id', $event->user_id)->get();

        if ($accountRoles->isEmpty()) {
            return response()->json(['error' => 'Account role not found'], 404);
        }

        // Find the first account with role_id = 2 or 1
        $serviceProvider = $accountRoles->filter(function ($account) {
            return in_array($account->role_id, [2, 1]);
        })->first();

        if (!$serviceProvider) {
            return response()->json(['error' => 'Service provider with role_id 2 or 1 not found'], 404);
        }

        return response()->json(['service_provider_name' => $serviceProvider->service_provider_name], 200);

    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}



public function getServiceProviederName($eventId, $userId)
{
    try {
        $event = Event::where('id', $eventId)->where('user_id', $userId)->first();

        if (!$event) {
            return response()->json(['error' => 'Event not found'], 404);
        }

        $accountRole = AccountRole::where('user_id', $event->user_id)->first();

        if (!$accountRole) {
            return response()->json(['error' => 'Account role not found'], 404);
        }

        return response()->json(['service_provider_name' => $accountRole->service_provider_name], 200);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Failed to retrieve service provider name'], 500);
    }
}
    public function getEventsByUserId(Request $request)
    {
        $userId = Auth::id();
        $events = Event::where('user_id', $userId)->get();
        return response()->json($events);
    }


    public function declineEventStatus(Request $request, $eventId)
{
    try {
        $event = Event::find($eventId);

        if (!$event) {
            return response()->json(['error' => 'Event not found'], 404);
        }

        $event->update(['status' => 'declined']);

        return response()->json(['message' => 'Event has been declined'], 200);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}


    public function updateEventStatus(Request $request, $eventId)
    {
        try {
            $event = Event::find($eventId);

            if (!$event) {
                return response()->json(['error' => 'Event not found'], 404);
            }

            // Update the event status to 'scheduled'
            $event->update(['status' => 'scheduled']);

            // Dispatch the EventCreatedApprovedEvent
            EventCreatedApprovedEvent::dispatch($event);

                // Trigger the feedback email to guests
            if ($event->status === 'complete') {
                $this->sendFeedbackEmails($event);
            }
            return response()->json(['message' => 'Booking approved! Your event is now scheduled!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }   

    public function updateEventStatusComplete(Request $request, $eventId)
    {
        try {
            $event = Event::find($eventId);

            if (!$event) {
                return response()->json(['error' => 'Event not found'], 404);
            }

            // Update the event status to 'scheduled'
            $event->update(['status' => 'complete']);

            // Dispatch the EventCreatedApprovedEvent
            // EventCreatedApprovedEvent::dispatch($event);

                // Trigger the feedback email to guests
            if ($event->status === 'complete') {
                $this->sendFeedbackEmails($event);
            }
            return response()->json(['message' => 'Event is done, it is now complete'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }   
    protected function sendFeedbackEmails(Event $event)
    { 
        // Retrieve all guests for the event
        $guests = Guest::where('event_id', $event->id)->get();

        // Iterate over each guest and send an email
        foreach ($guests as $guest) {
            Mail::to($guest->email)->send(new FeedbackRequestMail($event, $guest));
        }
    }
    public function sendEventScheduleNotice(Request $request, $eventId)
{
    try {
        $event = Event::find($eventId);

        if (!$event) {
            return response()->json(['message' => 'Event not found'], 404);
        }

        $guests = $event->guests;

        if (!$guests) {
            return response()->json(['message' => 'No guests found for this event'], 404);
        }

        foreach ($guests as $guest) {
            $guest->notify(new EventScheduleNotice($event, $guest));
        }

        return response()->json(['message' => 'Event schedule notice sent successfully']);
    } catch (\Throwable $th) {
        return response()->json(['message' => $th->getMessage()], 500);
    }
}
    public function sendEventScheduleNoticeFacade(Request $request, $eventId)
    {
        $event = Event::find($eventId);
        $guests = $event->guests;

        Notification::send($guests, new EventScheduleNotice($event, $guests));

        return response()->json(['message' => 'Event schedule notice sent successfully']);
    }


    public function getUserBookingEvents($eventId)
{
    $userId = Auth::id();

    $events = Event::where('id', $eventId)
        ->where('user_id', $userId)
        ->first();

    if (!$events) {
        return response()->json(['error' => 'Event not found'], 404);
    }

    return response()->json($events);
}

public function updatePaymentStatus(Request $request, $id)
{
    $request->validate([
        'payment_status' => 'required|in:Downpayment,Paid',  
    ]);
    $event = Event::find($id);

    if (!$event) {
        return response()->json(['message' => 'Event not found'], 404);
    }
    $event->payment_status = $request->input('payment_status');
    $event->save();
    return response()->json([
        'message' => 'Payment status updated successfully',
        'payment_status' => $event->payment_status
    ], 200);
}

public function getEventsWithMyServices()
{
    try {
        // Ensure user is authenticated
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized. Please log in.'], 401);
        }

        // Check if the user is either a service provider or has role_id 1
        if (!in_array($user->role_id, [1, 3])) { // Allow roles 1 and 3
            return response()->json(['message' => 'Access restricted to authorized roles only.'], 403);
        }


        // Retrieve events where the user's services are included
        $events = DB::table('event_services_providers')
        ->join('events', 'event_services_providers.event_id', '=', 'events.id')
        ->join('services', 'event_services_providers.service_id', '=', 'services.id')
        ->where('event_services_providers.user_id', $user->id)
        ->select(
            'events.*', // Select all columns from the events table
            'services.id as service_id',
            'services.serviceName as service_name',
            'event_services_providers.package_id as package_id'
        )
        ->get();
    

        // Return the result
        return response()->json(['events' => $events], 200);

    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
        ], 500);
    }
}



    
}
