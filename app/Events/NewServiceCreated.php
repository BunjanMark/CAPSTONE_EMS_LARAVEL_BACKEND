<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewServiceCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $service;
    /**
     * Create a new event instance.
     */
    public function __construct($service)
    {
        $this->service = $service;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    // public function broadcastOn(): array
    // {
    //     return [
    //         new PrivateChannel('channel-name'),
    //     ];
    // }
    public function broadcastOn()
    {
        return new Channel('admin-channel');
    }
    public function broadcastWith()
{
    \Log::debug('New service event data:', ['service' => $this->service]);

    return [
        'name' => $this->service->serviceName,
        'message' => "A new service has been created!",
    ];
}

    public function broadcastAs()
    {
        return 'new-service'; // Customize the event name if necessary
    }
}
