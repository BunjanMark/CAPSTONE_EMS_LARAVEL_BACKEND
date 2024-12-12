<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ExampleCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'example:run {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Runs an example command';

    /**
     * Execute the console command.
     */
    
    public function handle()
    
    {
        $name = $this->argument('name');
        $this->info("Hello, {$name}!");
        //
    }
}
