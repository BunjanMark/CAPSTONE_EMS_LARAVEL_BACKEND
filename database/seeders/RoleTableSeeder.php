<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table("roles")->insert([
            "role_name" => "Admin",
            "role_type" => "admin",
        ]);

        DB::table("roles")->insert([
            "role_name" => "User",
            "role_type" => "user",
        ]);

        DB::table("roles")->insert([
            "role_name" => "Manager",
            "role_type" => "service-provider",
        ]);
    }
}