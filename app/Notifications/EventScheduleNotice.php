<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
 
class EventScheduleNotice extends Notification
{
    use Queueable;
    private $event;
    private $guests;
    /**
     * Create a new notification instance.
     */
    public function __construct($event, $guests)
    {
        $this->event = $event;
        $this->guests = $guests;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        $mailMessage = (new MailMessage)
            ->subject('Event Schedule Notice')
            ->greeting('Dear ' . $this->guests->name)
            ->line('You are scheduled to attend the event: ' . $this->event->name)
            ->line('Event Date: ' . $this->event->date)
            ->line('Event Time: ' . $this->event->time)
            ->action('View Event Details', route('events.show', $this->event->id));

        return $mailMessage;
    }


    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
