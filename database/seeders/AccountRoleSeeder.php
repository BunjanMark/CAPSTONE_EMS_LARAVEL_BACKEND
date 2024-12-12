<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AccountRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table("account_roles")->insert([
            "user_id" => 1,
            "role_id" => 1,
            "service_provider_name" => "Admin",
            "description" => "Admin's Service provider account description",
        ]);

        DB::table("account_roles")->insert([
            "user_id" => 1,
            "role_id" => 3,
            "service_provider_name" => "Allrounded Service Provider",
            "description" => "Admin's Service provider account description",
        ]);
        DB::table("account_roles")->insert([
            "user_id" => 3,
            "role_id" => 3,
            "service_provider_name" => "Diwata pares services",
            "description" => "Service provider account description is diwata??",
        ]);
        DB::table("account_roles")->insert([
            "user_id" => 2,
            "role_id" => 2,
            "service_provider_name" => "Customer",
            "description" => "Customer",
        ]);    
  
    }
}
