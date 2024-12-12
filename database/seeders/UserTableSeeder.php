<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table("users")->insert([
            "role_id" => 1,
            "name" => "Admin",
            "email" => "admin@gmail.com",
            "password" => bcrypt("pass@admin"),
            "phone_number" =>  "1234567890",
        ]);

        DB::table("users")->insert([
            "role_id" => 2,
            "name" => "Customer",
            "email" => "customer@gmail.com",
            "password" => bcrypt("pass@customer"),
            "phone_number" =>  "1234567890",
        ]);

        DB::table("users")->insert([
            "role_id" => 3,
            "name" => "diwata",
            "email" => "diwata@gmail.com",
            "password" => bcrypt("pass@diwata"),
            "phone_number" =>  "1234567890",
        ]);
    }
}