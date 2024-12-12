<?php
namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a package
        DB::table('packages')->insert([
            'packageName' => 'Wedding Package Special',
            'eventType' => 'Wedding',
            'packageType' => 1, //it's predefined
            'totalPrice' => 1000.00,
            
            'coverPhoto' => 'https://example.com/package1.jpg',
            'services' => json_encode("[1, 2]"), // Store services as JSON
        ]);
            // Link associated service IDs to the package
        DB::table('package_services')->insert([
            'package_id' => 1,
            'service_id' => 1,
        ]);
 
        DB::table('package_services')->insert([
            'package_id' => 1,
            'service_id' => 2,
        ]);
 
    }
}