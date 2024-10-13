<?php

namespace App\Channels;

use App\Models\User;
use Exception;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class KavenegarOtpSmsChannel
{

    private string $apiKey;
    private User $user;

    public function __construct()
    {
        $this->apiKey = config('services.kavenegar.key');
        $this->user = Auth::user();
    }

    public function send(object $notifiable, Notification $notification): void
    {
        $request = $notification->toSMS($notifiable);

        $token = $request['token'];
        $template = $request['template'];
        $phoneNumber = $request['receptor'];

        try {
            $response = Http::asJson()->acceptJson()->post("https://api.kavenegar.com/v1/{$this->apiKey}/verify/lookup.json?receptor={$phoneNumber}&token={$token}&template={$template}");
            $this->handleResponse($response, $request);
        } catch (Exception) {
            $this->user->logs()->create([
                'request'  => $request,
                'response' => $response->json(),
                'type'     => 'OTP',
                'status'   => 'Failed',
            ]);
        }
    }

    protected function handleResponse($response, $request)
    {
        if ($response->successful()) {
            $this->user->decrement('balance', 0.04);
            $this->user->logs()->create([
                'request'  => $request,
                'response' => $response->json(),
                'type'     => 'OTP',
                'status'   => 'Sent',
            ]);
        } else {
            $this->user->logs()->create([
                'request'  => $request,
                'response' => $response->json(),
                'type'     => 'OTP',
                'status'   => 'Failed',
            ]);
        }
    }
}
