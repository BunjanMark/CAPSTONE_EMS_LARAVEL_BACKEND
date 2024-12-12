<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('events')->insert([
            'name' => 'Jane Does Birthday!!',
            'type' => 'Birthday',
            'totalPrice' => 551000.00,
            'pax' => 1000,
            'date' => '2027-08-01',
            'time' => '12:00:00',
            'location' => 'New York, USA',
            'description' => 'A wedding event with a lot of guest, nice place, etc.',
            'status' => 'scheduled',
            'user_id' => 2,  
            'coverPhoto' => 'https://ktmddejbdwjeremvbzbl.supabase.co/storage/v1/object/sign/capstone/test_uploads/package_cover_1733205105421.jpg?token=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1cmwiOiJjYXBzdG9uZS90ZXN0X3VwbG9hZHMvcGFja2FnZV9jb3Zlcl8xNzMzMjA1MTA1NDIxLmpwZyIsImlhdCI6MTczMzIwNTEwOCwiZXhwIjoxNzMzMjA4NzA4fQ.LKo4nUyFkxgiTVe-vZAds599_MAgiVclzTjhfTm4_p4',
            'packages' => json_encode("[1]"),
        ]);

        DB::table('event_packages')->insert([
            'event_id' => 1,
            'package_id' => 1,
        ]);
    }
}
