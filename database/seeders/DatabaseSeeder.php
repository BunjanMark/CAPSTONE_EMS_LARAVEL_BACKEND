<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call(RoleTableSeeder::class);
        $this->call(UserTableSeeder::class);
        $this->call(AccountRoleSeeder::class);
        $this->call(ServiceSeeder::class);
        $this->call(PackageSeeder::class);
        $this->call(EventSeeder::class);

    }
}
