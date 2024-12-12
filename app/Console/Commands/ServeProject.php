<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class ServeProject extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:serve-project {host=localhost} {port=8000}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Runs a project in the specified host and port';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $host = $this->argument('host');
        $port = $this->argument('port');

        $this->info("Starting project on http://{$host}:{$port}");

        $process = new Process(["php", "artisan", "serve", "--host={$host}", "--port={$port}"]);
        $process->setTimeout(null); // Run indefinitely

        $process->run(function ($type, $buffer) {
            echo $buffer;
        });
    }
}
