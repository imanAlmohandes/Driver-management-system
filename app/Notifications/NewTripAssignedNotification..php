<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
// use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;
use App\Models\Trip; // Import the Trip model

class NewTripAssignedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public Trip $trip; // Use the Trip model for type-hinting

    public function __construct(Trip $trip)
    {
        $this->trip = $trip;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'text'  => 'New trip assigned: ' . $this->trip->fromStation->name . ' -> ' . $this->trip->toStation->name,
            'icon'  => 'fa-route',
            'color' => 'info',
            'route' => route('driver.my_work') // Always take the driver to their main work page
        ];
    }

    // public function toBroadcast(object $notifiable): BroadcastMessage
    // {
    //     return new BroadcastMessage($this->toArray($notifiable));
    // }
}
