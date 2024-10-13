<?php

namespace App\Notifications;

use App\Channels\KavenegarOtpSmsChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class OtpNotification extends Notification
{
    use Queueable;

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
        return ['database', KavenegarOtpSmsChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toSms(object $notifiable): array
    {
        return [
            'template' => 'otp',
            'receptor' => $this->data['receptor'],
            'token'    => $this->data['token'],
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
            'template'   => 'otp',
            'receptor' => $this->data['receptor'],
            'token'      => $this->data['token'],
            'created_at' => now()
        ];
    }
}
