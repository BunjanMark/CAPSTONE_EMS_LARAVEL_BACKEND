<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Package;
use Illuminate\Support\Facades\DB;
use App\Events\PackageCreatedNotification;
use Illuminate\Support\Facades\Auth;
use App\Events\PackageCreatedEvent;
class PackageController extends Controller
{
    // Method to fetch all packages
    public function index()
    {
        try {
            // Eager load 'services' relationship for all packages
            $packages = Package::with('services')->get();
    
            // Return the result as JSON with a 200 status code
            return response()->json($packages, 200);
        } catch (\Exception $e) {
            // Handle errors and return a 500 status code with an error message
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
    
public function update(Request $request, $id)
{
    DB::beginTransaction(); // Start the transaction

    try {
        // Find the package by its ID
        $package = Package::find($id);

        if (!$package) {
            return response()->json([
                'status' => 'error',
                'message' => 'Package not found.',
            ], 404);
        }

        // Validate the incoming data
        // Validate the incoming data
        $validatedData = $request->validate([
            'packageName' => 'nullable|string|max:255',
            'eventType' => 'nullable|string',
            'services' => 'nullable|array', // Validate services as an array
            'services' => 'nullable|array', // Validate services as an array
            'services.*' => 'integer', // Ensure each service is an integer
            'totalPrice' => 'nullable|numeric|min:1',
            'pax' => 'nullable|numeric|min:1',
            'coverPhoto' => 'nullable|url', // Ensure it's a valid URL
        ]);

        // Ensure that 'services' is set to an empty array if not provided
        if (!isset($validatedData['services'])) {
            $validatedData['services'] = []; // Default to an empty array if services are not provided
        }

        // Update the package fields, using the validated data
        $package->update([
            'packageName' => $validatedData['packageName'] ?? $package->packageName,
            'eventType' => $validatedData['eventType'] ?? $package->eventType,
            'totalPrice' => $validatedData['totalPrice'] ?? $package->totalPrice,
            'pax' => $validatedData['pax'] ?? $package->pax,
            'coverPhoto' => $validatedData['coverPhoto'] ?? $package->coverPhoto,
            'services' => json_encode($validatedData['services']), // Update the services as a JSON-encoded string
        ]);

        // Sync the package_services pivot table
        if (isset($validatedData['services']) && count($validatedData['services']) > 0) {
            // Synchronize the related services in the pivot table
            $package->services()->sync($validatedData['services']); // Automatically handles updates
        }

        DB::commit(); // Commit the transaction

        return response()->json([
            'status' => 'success',
            'message' => 'Package updated successfully.',
            'package' => $package->load('services'), // Load related services for the response
        ], 200);

    } catch (\Illuminate\Validation\ValidationException $e) {
        DB::rollBack(); // Rollback the transaction in case of validation failure
        return response()->json([
            'status' => 'error',
            'message' => 'Validation failed.',
            'errors' => $e->errors(),
        ], 422);
    } catch (\Throwable $th) {
        DB::rollBack(); // Rollback the transaction in case of other errors
        return response()->json([
            "status" => "error",
            "message" => $th->getMessage(),
        ], 500);
    }
}

    // Method to store a new package
    public function store(Request $request)
{
    DB::beginTransaction(); // Start the transaction

    try {
        // Validate the incoming data
        $validatedData = $request->validate([
            'packageName' => 'required|string|max:255',
            'eventType' => 'required|string',
            'packageType' => 'nullable|boolean',  // packageType is optional here    
            'services' => 'nullable|array', // Make services nullable
            'services.*' => 'integer',
            'totalPrice' => 'required|numeric|min:1',
            'pax' => 'required|integer|min:1',
            'coverPhoto' => 'nullable|url', // Ensure it's a valid URL
 
        ]);

        if (!isset($validatedData['services'])) {
            $validatedData['services'] = [];
        }
        $packageType = $validatedData['packageType'] ?? true;

        // Create the package in the database
        $package = Package::create([
            'packageName' => $validatedData['packageName'],
            'eventType' => $validatedData['eventType'],
            'packageType' => $packageType,  // Assign the correct package type
            'totalPrice' => $validatedData['totalPrice'],
            'pax' => $validatedData['pax'],
            'coverPhoto' => $validatedData['coverPhoto'],
            'services' => json_encode($validatedData['services']),
        ]);

        event(new PackageCreatedEvent($package));
        // $user = auth()->user(); // Get the current user
        // event(new PackageCreatedNotification($package));
        // Link associated service IDs to the package
        if (isset($validatedData['services']) && count($validatedData['services']) > 0) {
            foreach ($validatedData['services'] as $serviceId) {
                DB::table('package_services')->insert([
                    'package_id' => $package->id,
                    'service_id' => $serviceId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        DB::commit(); // Commit the transaction

        return response()->json($package, 201); // Return the created package with 201 status

    } catch (\Illuminate\Validation\ValidationException $e) {
        DB::rollBack(); // Rollback the transaction in case of validation failure
        return response()->json([
            'status' => 'error',
            'message' => 'Validation failed.',
            'errors' => $e->errors(),
        ], 422);
    } catch (\Throwable $th) {
        DB::rollBack(); // Rollback the transaction in case of other errors
        return response()->json([
            "status" => "error",
            "message" => $th->getMessage()
        ], 500);
    }
}
    public function getAllServicesInPackages(Request $request)
    {
        $packages = Package::all();
        $servicesInPackages = [];

        foreach ($packages as $package) {
            $services = json_decode($package->services, true);
            $servicesInPackages = array_merge($servicesInPackages, $services);
        }

        return response()->json($servicesInPackages);
    }
    public function destroy(Request $request, $id)
    {
        DB::beginTransaction(); // Start the transaction

        try {
            // Find the package to delete
            $package = Package::find($id);

            if (!$package) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Package not found.',
                ], 404);
            }

            // Delete the package services
            DB::table('package_services')->where('package_id', $id)->delete();

            // Delete the package
            $package->delete();

            DB::commit(); // Commit the transaction

            return response()->json([
                'status' => 'success',
                'message' => 'Package deleted successfully.',
            ], 200);

        } catch (\Throwable $th) {
            DB::rollBack(); // Rollback the transaction in case of error
            return response()->json([
                "status" => "error",
                "message" => $th->getMessage()
            ], 500);
        }
    }

    public function getServicesInPackage(Request $request)
    {
        $userId = Auth::id();
        $packages = Package::where('user_id', $userId)->get();
        $servicesInPackages = [];

        foreach ($packages as $package) {
            $services = json_decode($package->services, true);
            $servicesInPackages = array_merge($servicesInPackages, $services);
        }

        return response()->json($servicesInPackages);
    }
}
