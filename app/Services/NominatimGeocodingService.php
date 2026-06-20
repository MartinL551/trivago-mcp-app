<?php

namespace App\Services;

use App\Services\Concerns\Coordinates;
use App\Services\Concerns\GeocodingService;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Log;

class NominatimGeocodingService implements GeocodingService
{
    private const BASE_URL = 'https://nominatim.openstreetmap.org/search';

    public function geocode(string $city, string $country = '', string $landmark = ''): ?Coordinates
    {
        $headers = [
            'User-Agent' => 'TrivagoMcpApp/1.0 (https://github.com/MartinL551/trivago-mcp-app)',
        ];

        $url = $this::BASE_URL;
        $landmark && $landmark != '' ? '+' . $landmark : '';
        $query = "{$url}?q={$city}{$landmark},{$country}&format=jsonv2&limit=1"; 

        $response = $this->sendRequest($headers, $query);

        $response->throw(function ($response, $e) {
            Log::error('Nominatim Geocoding failed', [
                'body' => $response->body(),
                'url' => $response->effectiveUri(),
            ]);
        });

        $results = $response->json() ?? [];

        Log::info('Geocode Check Response', [
            'status' => $response->status(),
            'body' => $response->body(),
            'decoded' => $results[0],
        ]);

        if(count($results) <= 0){
            return null;
        }

        return new Coordinates($results[0]['lat'], $results[0]['lon']);
    }

    private function sendRequest(Array  $headers, String $query): Response
    {
        return Http::connectTimeout(5)->timeout(20)->withHeaders($headers)->get($query);
    }
}