<?php
namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('services')->insert([
            'user_id' => 3,
            'serviceName' => 'Diwata pares',
            'serviceCategory' => 'Food Catering',
            'serviceFeatures' => 'Feature 1, Feature 2, Feature 3',
            'servicePhotoURL' => 'https://example.com/service1.jpg',
            'verified' => true,
            'location' => 'Location 1',
            'basePrice' => 10.99,
            'pax' => 1,
            'requirements' => 'Requirement 1, Requirement 2',
            'availability_status' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('services')->insert([
            'user_id' => 3,
            'serviceName' => 'PITIKKKK',
            'serviceCategory' => 'Photography',
            'serviceFeatures' => 'Feature 4, Feature 5, Feature 6',
            'servicePhotoURL' => 'https://example.com/service2.jpg',
            'verified' => true,
            'location' => 'Location 2',
            'basePrice' => 20.99,
            'pax' => 2,
            'requirements' => 'Requirement 3, Requirement 4',
            'availability_status' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}