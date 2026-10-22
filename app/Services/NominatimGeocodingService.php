<?php

namespace App\Services;

use App\Services\Concerns\Coordinates;
use App\Services\Concerns\GeocodingService;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Log;
use App\Models\Location;

class NominatimGeocodingService implements GeocodingService
{
    private const BASE_URL = 'https://nominatim.openstreetmap.org/search';

    public function geocode(string $city, string $country = '', string $landmark = ''): ?Coordinates
{
        if($cachedLocation = $this->checkCached($city, $country, $landmark)){

            Log::info('Geocode Check Found in cached table', [
                'long' => $cachedLocation->longitude,
                'lat' => $cachedLocation->latitude,
            ]);

            return new Coordinates($cachedLocation->latitude, $cachedLocation->longitude);
        }
        
        $headers = [
            'User-Agent' => 'TrivagoMcpApp/1.0 (https://github.com/MartinL551/trivago-mcp-app)',
        ];

        $url = $this::BASE_URL;
        $landmarkQuery = $landmark !== '' ? $landmark.',' : '';
        $query = "{$url}?q={$landmarkQuery}{$city},{$country}&format=jsonv2&limit=1"; 

        Log::info('Geocode Query With', [
            'url' => $url,
            'query' => $query, 
        ]);

        $response = $this->sendRequest($headers, $query);

        $response->throw(function ($response, $e) {
            Log::error('Nominatim Geocoding failed', [
                'body' => $response->body(),
                'url' => $response->effectiveUri(),
            ]);
        });

        $results = $response->json() ?? [];

        if(count($results) <= 0){
            if ($landmark !== '') {
                return $this->geocode($city, $country);
            }

            return null;
        }

        Location::updateOrCreate(
            [
                'city' => $city,
                'country' => $country,
                'landmark' => $landmark,
            ],
            [
                'latitude' => $results[0]['lat'],
                'longitude' => $results[0]['lon'],
            ]
        );

        return new Coordinates($results[0]['lat'], $results[0]['lon']);
    }

    private function checkCached(string $city, string $country = '', string $landmark = '')
    {
        return Location::where([
            'city' => $city,
            'country' => $country,
            'landmark' => $landmark,
        ])->first();
    }

    private function sendRequest(Array  $headers, String $query): Response
    {
        return Http::connectTimeout(5)->timeout(20)->withHeaders($headers)->get($query);
    }
}