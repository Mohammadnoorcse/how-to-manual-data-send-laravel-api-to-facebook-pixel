<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class FacebookApiService
{
    protected $client;
    protected $pixelId;
    protected $accessToken;
    protected $testEventCode;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://graph.facebook.com/v19.0/',
        ]);
        $this->pixelId = config('services.facebook.pixel_id');
        $this->accessToken = config('services.facebook.conversions_api_token');
        $this->testEventCode = config('services.facebook.test_event_code');
    }

    /**
     * Sends a generic event to the Facebook Conversions API.
     *
     * @param string $eventName The name of the event (e.g., 'ViewContent', 'AddToCart').
     * @param array $userData User data, such as email and phone number.
     * @param array $customData Event-specific custom data.
     * @return void
     */
    public function sendEvent(string $eventName, array $userData, array $customData)
    {
        $endpoint = "{$this->pixelId}/events";
        $eventId = (string) Str::uuid(); // For event deduplication

        $payload = [
            'access_token' => $this->accessToken,
            'data' => [
                [
                    'event_name' => $eventName,
                    'event_time' => time(),
                    'event_id' => $eventId,
                    'event_source_url' => url()->current(),
                    'action_source' => 'website',
                    'user_data' => $userData,
                    'custom_data' => $customData,
                ],
            ],
             'test_event_code' => $this->testEventCode, // Remove this line for production
        ];

        try {
            $response = $this->client->post($endpoint, ['json' => $payload]);
            Log::info('Facebook Conversion API event sent.', [
                'event_name' => $eventName,
                'response' => $response->getBody()->getContents(),
            ]);
        } catch (\Exception $e) {
            Log::error('Facebook Conversion API error.', [
                'event_name' => $eventName,
                'message' => $e->getMessage(),
            ]);
        }
    }
}