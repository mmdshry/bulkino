<?php

namespace App\Notifications;

use App\Channels\KavenegarSmsChannel;
use App\Traits\MessageFormaterTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class SmsNotification extends Notification
{
    use Queueable, MessageFormaterTrait;

    private array $data;

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
        return ['database', KavenegarSmsChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toSms(object $notifiable): array
    {
        return [
            'message' => $this->line($this->data['message'])->cancel(),
            'receptor' => $this->data['receptor']
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
            'message' => $this->data['message'],
            'receptor' => $this->data['receptor'],
            'created_at' => now()
        ];
    }
}
