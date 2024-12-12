<?php 

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\ExpoMessage;

class PackageCreatedNotification extends Notification
{
    use Queueable;

    public function __construct(public $package)
    {
        //
    }

    public function via($notifiable)
    {
        return ['expo', 'database'];
    }
    public function routeNotificationForDatabase($notification)
{
    return [
        'user_id' => $this->id,
        'notification_type' => get_class($notification),
        'data' => $notification->toArray($this),
    ];
}
    public function toExpo($notifiable)
    {
        return (new ExpoMessage())
            ->setTitle('New Package Created')
            ->setBody("Package '{$this->package->packageName}' has been added.")
            ->setData(['package_id' => $this->package->id])
            ->playSound();
    }

    public function toDatabase($notifiable)
{
    $data = [
        'user_id' => $notifiable->id,
        'notification_type' => get_class($this),
        'data' => [
            'package_id' => $this->package->id,
            'package_name' => $this->package->packageName,
            // Additional data as needed
        ],
    ];

    \Log::info('Notification data:', $data);

    return $data;
}
}