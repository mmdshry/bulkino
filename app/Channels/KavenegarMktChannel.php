<?php

namespace App\Channels;

use App\Models\User;
use Auth;
use Exception;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;

class KavenegarMktChannel
{
    private string $apiKey;

    private string $sender;

    private User $user;

    public function __construct()
    {
        $this->apiKey = config('services.kavenegar.key');
        $this->sender = config('services.kavenegar.sender');
        $this->user = Auth::user();
    }

    public function send(object $notifiable, Notification $notification): void
    {
        $request = $notification->toSMS($notifiable);

        $message = $request['message'];
        $phoneNumbers = implode(',', $request['receptors']);

        try {
            $response = Http::asJson()->acceptJson()->post("https://api.kavenegar.com/v1/{$this->apiKey}/sms/send.json?receptor={$phoneNumbers}&sender={$this->sender}&message={$message}");
            $this->handleResponse($response, $request);
        } catch (Exception) {
            $this->user->logs()->create([
                'request'  => $request,
                'response' => $response->json(),
                'type'     => 'MKT',
                'status'   => 'Failed',
            ]);
        }
    }

    protected function handleResponse($response, $request)
    {
        if ($response->successful()) {
            $this->user->decrement('balance', 0.04 * count($request['receptors']));
            $this->user->logs()->create([
                'request'  => $request,
                'response' => $response->json(),
                'type'     => 'MKT',
                'status'   => 'Sent',
            ]);
        } else {
            $this->user->logs()->create([
                'request'  => $request,
                'response' => $response->json(),
                'type'     => 'MKT',
                'status'   => 'Failed',
            ]);
        }
    }
}
