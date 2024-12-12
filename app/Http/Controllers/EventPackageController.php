<?php

namespace App\Http\Controllers;
use App\Models\EventPackage;
use App\Models\Package;
use Illuminate\Http\Request;

class EventPackageController extends Controller
{
    //

    public function eventPackage($eventId)
    {
        $eventPackages = EventPackage::where('event_id' , $packageId)->get();
        return response()->json($eventPackages);
    }

    public function getEventPackages($eventId)
    {
        try {
            $eventPackages = EventPackage::where('event_id', $eventId)->get();

        $packages = [];

        foreach ($eventPackages as $eventPackage){
            $package = Package::find($eventPackage->package_id);
            $packages[] = [
                'id' => $package->id,
                'packageName' => $package->packageName,
                'eventType' => $package->eventType,
                'packageType' => $package->packageType,
                'services' => $package->services,
                'totalPrice' => $package->totalPrice,
                'coverPhoto' => $package->coverPhoto,
            ];
        }

        return response()->json($packages);
        } catch (\Throwable $th) {
            //throw $th;
            throw $th;
        }
    }

}
