<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\Event;
use Illuminate\Support\Facades\Http;
class FetchEventServicesJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    protected $eventId;

    public function __construct($eventId)
    {
        $this->eventId = $eventId;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $response = Http::get("http://192.168.1.13:8000/api/events/{$this->eventId}/services");

        // Process the API response or store the result in cache or database
        if ($response->successful()) {
            $services = $response->json();
            // Save services data, store in cache, or log them for later use
        }
    }
}
