<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GeocodingService
{
    public function search($address)
    { 
        $url = 'https://nominatim.openstreetmap.org/search';
        $response = Http::withHeaders([
            'User-Agent' => 'LaravelApp-Geocoder/1.0 (fiononantsoarakotojaona@gmail.com)'
        ])->get($url, [
            'format' => 'json',
            'addressdetails' => 1,
            'q' => $address,
            'limit' => 1,
            'countrycodes' => 'mg',
        ]);

        if ($response->successful() && !empty($response[0])) {
            $data = $response[0];
            $details = $data['address'] ?? [];

            return [
                'latitude' => $data['lat'] ?? null,
                'longitude' => $data['lon'] ?? null,
                'quartier' => $details['suburb'] ?? $details['neighbourhood'] ?? null,
                'ville' => $details['city'] ?? $details['town'] ?? $details['village'] ?? null,
                'region' => $details['state'] ?? null,
                'pays' => $details['country'] ?? null,
                'adresse_complete' => $data['display_name'] ?? null,
            ];
        }

        return null;
    }
}
