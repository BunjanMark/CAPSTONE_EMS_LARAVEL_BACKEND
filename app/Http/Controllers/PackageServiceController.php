<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\PackageService;

class PackageServiceController extends Controller
{
    //
    public function packageService($packageId)
    {
        $packageServices = PackageService::where('package_id', $packageId)->get();
        return response()->json($packageServices);
    }
    
    public function getPackageServices($packageId)
    {
        $packageServices = PackageService::where('package_id', $packageId)->get();
    
        $services = [];
        foreach ($packageServices as $packageService) {
            $service = Service::find($packageService->service_id);
            $services[] = [
                'id' => $service->id,
                'serviceName' => $service->serviceName,
                'serviceCategory' => $service->serviceCategory,
                'location' => $service->location,
                'serviceFeatures' => array_map('trim', explode(',', $service->serviceFeatures)),
                'servicePhotoURL' => $service->servicePhotoURL,
                'verified' => $service->verified,
                'basePrice' => $service->basePrice,
                'pax' => $service->pax,
                'requirements' => $service->requirements,
            ];
        }
    
        return response()->json($services);
    }

    public function getPackageServiceProvidersId($packageId){
        try {
            $packageServices = PackageService::where('package_id', $packageId)->get();
    
            $servicesProviders = [];
            foreach ($packageServices as $packageService) {
                $service = Service::find($packageService->service_id);
                $servicesProviders[] = [
                    'user_id' => $service->user_id,
                    'serviceName' => $service->serviceName,
                    'serviceCategory' => $service->serviceCategory,
                    'location' => $service->location,
                    'serviceFeatures' => array_map('trim', explode(',', $service->serviceFeatures)),
                    'servicePhotoURL' => $service->servicePhotoURL,
                    'verified' => $service->verified,
                    'basePrice' => $service->basePrice,
                    'pax' => $service->pax,
                    'requirements' => $service->requirements,
                ];
            }
        
            return response()->json($servicesProviders);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['message' => 'Error fetching package services.'], 500);


        }
    }

 
}
