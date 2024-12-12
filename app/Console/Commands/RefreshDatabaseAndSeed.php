<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RefreshDatabaseAndSeed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:refresh-seed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh the database and seed it';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //refresh the database
        $this->call('migrate:refresh');
        // populate the database
        $this->call('db:seed');

        $this->info('Database refreshed and seeded successfully.');
    }
}
