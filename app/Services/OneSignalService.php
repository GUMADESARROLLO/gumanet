<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class OneSignalService
{
    public function sendToUser($userId, $title, $message, $data = [])
    {
        try {
            $client = new Client([
                'verify' => false, // Desactiva verificación SSL (solo para pruebas locales)
                'headers' => [
                    'Authorization' => 'Key ' . config('services.onesignal.api_key'),
                    'Content-Type'  => 'application/json; charset=utf-8',
                ],
            ]);

            $body = [
                'app_id' => config('services.onesignal.app_id'),
                'include_player_ids' => [$userId],
                'headings' => ['en' => $title],
                'contents' => ['en' => $message],
                'data' => $data,
            ];

            $response = $client->post(config('services.onesignal.api_url'), [
                'json' => $body,
            ]);

            $result = json_decode($response->getBody(), true);

            return $result;
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $errorBody = (string) $e->getResponse()->getBody();
                \Log::error('OneSignal error: ' . $errorBody);
                return ['error' => $errorBody];
            }

            \Log::error('OneSignal connection error: ' . $e->getMessage());
            return ['error' => $e->getMessage()];
        }
    }
}
