<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
// use Illuminate\Notifications\Messages\MailMessage;
// use Illuminate\Notifications\Messages\BroadcastMessage;

class AdminDashboardNotification extends Notification implements ShouldQueue
{//php artisan queue:work لازم يكون شغال بالتيرمنيل عشان اشوف الكيوو والاشعارات لما يتم انشاء سجل وقود او سجل صيانة
    use Queueable;
    public array $data;

    /**
     * Create a new notification instance.
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        // return ['mail'];
                return ['database'];

    }

    /**
     * Get the mail representation of the notification.
     */
    // public function toMail(object $notifiable): MailMessage
    // {
    //     return (new MailMessage)
    //         ->line('The introduction to the notification.')
    //         ->action('Notification Action', url('/'))
    //         ->line('Thank you for using our application!');
    // }
/**
     * Get the database representation of the notification.
     */
    public function toDatabase(object $notifiable): array
    {
        // This is what will be saved in the 'data' column of the notifications table
        return $this->data;
    }

    /**
     * Get the broadcastable representation of the notification.
     */
    // public function toBroadcast(object $notifiable): BroadcastMessage
    // {
    //     // This is what will be sent via Pusher/Echo
    //     return new BroadcastMessage($this->data);
    // }
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
