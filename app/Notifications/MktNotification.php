<?php

namespace App\Notifications;

use App\Channels\KavenegarMktChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class MktNotification extends Notification
{
    use Queueable;

    private array $message;

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
        return ['database', KavenegarMktChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toSms(object $notifiable): array
    {
        return [
            'message'   => $this->data['message'],
            'receptors' => $this->data['receptors']
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message'    => $this->data['message'],
            'receptors'  => $this->data['receptors'],
            'created_at' => now()
        ];
    }
}
