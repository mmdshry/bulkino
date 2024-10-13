<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class KavenegarSmsService
{
    protected string $apiKey;
    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.kavenegar.base_url');
        $this->apiKey = config('services.kavenegar.key');
    }

    /**
     * Send a single SMS message.
     *
     * @param string $receptor
     * @param string $message
     * @param string|null $sender
     * @return array
     */
    public function sendSms(string $receptor, string $message, string $sender = null): array
    {
        $response = Http::post("{$this->baseUrl}/{$this->apiKey}/sms/send.json", [
            'receptor' => $receptor,
            'message' => urlencode($message),
            'sender' => $sender,
        ]);

        return $this->handleResponse($response);
    }

    /**
     * Send multiple SMS messages.
     *
     * @param array $receivers
     * @param array $messages
     * @param array|null $senders
     * @return array
     */
    public function sendMultipleSms(array $receivers, array $messages, array $senders = null): array
    {
        if (count($receivers) !== count($messages) || ($senders && count($senders) !== count($messages))) {
            throw new \InvalidArgumentException('All input arrays must have the same length.');
        }

        $response = Http::post("{$this->baseUrl}/{$this->apiKey}/sms/sendarray.json", [
            'receptor' => $receivers,
            'message' => array_map('urlencode', $messages),
            'sender' => $senders,
        ]);

        return $this->handleResponse($response);
    }

    /**
     * Check the status of a sent message.
     *
     * @param int|string $messageId
     * @return array
     */
    public function getMessageStatus($messageId): array
    {
        $response = Http::get("{$this->baseUrl}/{$this->apiKey}/sms/status.json", [
            'id' => $messageId,
        ]);

        return $this->handleResponse($response);
    }

    /**
     * Cancel a sent message.
     *
     * @param int|string $messageId
     * @return array
     */
    public function cancelMessage($messageId): array
    {
        $response = Http::post("{$this->baseUrl}/{$this->apiKey}/sms/cancel.json", [
            'id' => $messageId,
        ]);

        return $this->handleResponse($response);
    }

    /**
     * Get account information.
     *
     * @return array
     */
    public function getAccountInfo(): array
    {
        $response = Http::get("{$this->baseUrl}/{$this->apiKey}/account/info.json");

        return $this->handleResponse($response);
    }

    /**
     * Handle the API response.
     *
     * @param \Illuminate\Http\Client\Response $response
     * @return array
     */
    protected function handleResponse($response): array
    {
        if ($response->failed()) {
            Log::error('Kavenegar API error', [
                'status' => $response->status(),
                'response' => $response->json(),
            ]);
            return [
                'success' => false,
                'message' => 'API request failed.',
                'error' => $response->json(),
            ];
        }

        return [
            'success' => true,
            'data' => $response->json(),
        ];
    }
}