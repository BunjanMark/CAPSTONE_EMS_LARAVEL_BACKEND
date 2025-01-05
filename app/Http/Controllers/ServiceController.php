<?php
namespace App\Http\Controllers;
use App\Notifications\NewServiceNotification;
use Illuminate\Http\Request;
use App\Models\Service;
use Illuminate\Support\Facades\Auth;
use App\Models\AccountRole; // Add this import
use Illuminate\Validation\ValidationException;
use App\Events\NewServiceCreated;
use App\Models\User;
use App\Events\ServiceCreatedEvent;
class ServiceController extends Controller
{



    /**
     * Fetch all services for the authenticated user.
     */
    public function index()
    {
        try {
            // Fetch services associated with the authenticated user's account role
            // $userId = $this->getUserIdFromRole();
            // $services = Service::where('user_id', $userId)->get();

            // fetch all services regardless of user ID
            $services = Service::all();  

            
            return response()->json($services, 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }
    // get own's services created
    public function getOwnServices()
    {
        try {
            // Fetch services associated with the authenticated user's account role
            // $userId = $this->getUserIdFromRole();
            // $services = Service::where('user_id', $userId)->get();

            // fetch all services regardless of user ID
            $services = Service::all();  

            
            return response()->json($services, 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }
    public function myService()
    {
        try {
            // $userId = $this->getUserIdFromRole(); // Get the user ID from account roles
            $userId = $this->getUserIdIfHasRoleId3();
            $services = Service::where('user_id', $userId)->get();
            return response()->json($services);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }
   
public function store(Request $request)
{
    try {
        // Check if the user has a permitted role
        $userRole = $this->getUserIdAndRole();
        if (!$userRole) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        if (!$request->has('servicePhotoURL')) {
            return response()->json(['message' => 'servicePhotoURL is required'], 422);
        }
        // Proceed with validation and service creation
        $validatedData = $request->validate([
            'serviceName' => 'required|string|max:255',
            'serviceCategory' => 'required|string|max:255',
            'serviceFeatures' => 'required|string|max:255',
            'servicePhotoURL' => 'nullable|string', 
            'verified' => 'boolean|nullable',
            'location' => 'nullable|string|max:255',
            'basePrice' => 'required|numeric|min:0',
            'events_per_day' => 'required|integer|min:1',
            'pax' => 'required|integer|min:1',
            'requirements' => 'nullable|string',
            'availability_status' => 'boolean',
        ]);

            // Attach the authenticated user's ID and role ID if needed
            $validatedData['user_id'] = $userRole['user_id'];
            $validatedData['role_id'] = $userRole['role_id']; // Only if you want to store role_id

            $service = Service::create($validatedData);
            event(new ServiceCreatedEvent($service));
 
            \Log::info("ServiceCreatedEvent fired for service: " . $service->serviceName);
            
            return response()->json([$service, 'message' => 'Service created successfully'], 201); // Created successfully
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['status' => 'error', 'errors' => $e->errors()], 422);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }
    



    /**
     * Fetch a specific service by ID.
     */
    public function show($id)
    {
        try {
            $userId = $this->getUserIdFromRole(); // Get the user ID from account roles
            $service = Service::findOrFail($id);

            // Check if the authenticated user owns the service
            if ($service->user_id !== $userId) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }

            return response()->json($service, 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    /**
     * Update a specific service by ID.
     */
    public function update(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'serviceName' => 'sometimes|required|string|max:255',
                'serviceCategory' => 'sometimes|required|string|max:255',
                'serviceFeatures' => 'sometimes|required|string|max:255',
                'basePrice' => 'sometimes|required|numeric|min:0',
                'pax' => 'sometimes|required|integer|min:1',
                'requirements' => 'nullable|string',
                'availability_status' => 'boolean',
                'events_per_day' => 'integer|min:1',
            ]);

            $userId = $this->getUserIdFromRole(); // Get the user ID from account roles
            $service = Service::findOrFail($id);

            // Check if the authenticated user owns the service
            if ($service->user_id !== $userId) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }

            $service->update($validatedData);
            return response()->json($service, 200); // Updated successfully
        } catch (ValidationException $e) {
            return response()->json(['status' => 'error', 'errors' => $e->errors()], 422);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    /**
     * Delete a specific service by ID.
     */
    public function destroy($id)
    {
        try {
            $userId = $this->getUserIdFromRole(); // Get the user ID from account roles
            $service = Service::findOrFail($id);

            // Check if the authenticated user owns the service
            if ($service->user_id !== $userId) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }

            $service->delete();
            return response()->json(['message' => 'Service deleted successfully'], 200); // Deleted successfully
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    /**
     * Get user ID from account roles.
     */
    private function getUserIdFromRole()
    {
        // Assuming the AccountRole model has a relationship to fetch the user ID
      
        $role = AccountRole::where('user_id', Auth::id())->first();

        if (!$role) {
            throw new \Exception('User does not have an associated account role');
        }

        return $role->user_id; // Assuming 'user_id' is the foreign key in account_roles
    }

    private function getUserIdAndRole()
    {
        $user = Auth::user();
        $accountRole = $user->accountRoles()->whereIn('role_id', [1, 3])->first();  

        if ($accountRole) {
            return [
                'user_id' => $user->id,
                'role_id' => $accountRole->role_id,
            ];
        }
        
        // If the user does not have a permitted role
        return null;
    }

        private function getUserIdIfHasRoleId3()
    {
        $user = Auth::user();
        $accountRole = $user->accountRoles()->where('role_id', 3)->first();
        return $accountRole ? $user->id : null;
    }

    public function triggerEventManually()
{
    try {
        // Manually create a service or get an existing service
        $newService = Service::find(1); // Example: Fetch service with ID = 1 (replace with your own logic)
        
        if (!$newService) {
            return response()->json(['message' => 'Service not found'], 404);
        }

        // Trigger the NewServiceCreated event manually
        event(new NewServiceCreated($newService));

        return response()->json(['message' => 'Event triggered successfully'], 200);
    } catch (\Exception $e) {
        return response()->json(['message' => $e->getMessage()], 500);
    }
}
}
