<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class OneSignalService
{
    public function sendToUser($userId, $title, $message, $data = [])
    {   
        $response = Http::withHeaders([
            'Authorization' => 'Key '. config('services.onesignal.api_key'),
            'Content-Type' => 'application/json; charset=utf-8',
        ])
        ->withoutVerifying() // Descomenta si estás probando local y tienes error SSL
        ->post(config('services.onesignal.api_url'), [
            'app_id' => config('services.onesignal.app_id'),
            'include_player_ids' => [$userId],
            'headings' => ['en' => $title],
            'contents' => ['en' => $message],
            'data' => $data,
        ]);

        return $response->json();
    
    }
}
