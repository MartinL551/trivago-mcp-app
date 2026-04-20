<?php

namespace App\Http\Controllers;

use App\Models\Accommodation;
use App\Services\OpenAIService;
use App\Services\TrivagoMcpService;
use Inertia\Inertia;

class DashboardController extends Controller
{   

    public function index(OpenAIService $aiService, TrivagoMcpService $trivagoMcpService)
    {
        $llmData = $aiService->extractSearchIntent('Area with lots of hikes in Germany. Only two adults are going. I am on budget of £2000. I want somewhere with mountains');


        $data = $trivagoMcpService->getAccommodationSearch($llmData);
        
        foreach($data as $accom) {
            Accommodation::updateOrCreate(
                [
                    'trivago_id' => $accom['accommodation_id'],
                ],
                [
                    'name' => $accom['accommodation_name'],
                    'postcode' => $accom['postal_code'],
                    'address' => $accom['address'],
                    'currency' => $accom['currency'],
                    'price_per_stay' => $accom['price_per_stay'],
                    'price_per_day' => $accom['price_per_night'],
                    'rating' => $accom['hotel_rating'],
                    'city' => $accom['country_city'],
                    'review_rating' => $accom['review_rating'],
                    'review_count' => (string) $accom['review_count'],
                    'amenites' => $accom['top_amenities'],
                    'trivago_url' => $accom['accommodation_url'],
                    'trivago_image_url' => $accom['main_image'],
                    'distance_string' => $accom['distance'],
                    'distance_to_center' => $accom['distance_to_city_center']['value'] ?? null,
                    'distance_units' => $accom['distance_to_city_center']['unit'] ?? null,
                    'desc' => $accom['description'] ?? '',
                ]
            );
        }


        $testAccom = Accommodation::first();

        $score = $aiService->getScoreForAccommidation($testAccom);

        dd($score);


        return Inertia::render('Dashboard', [
            'res' => $response,
        ]);
    }
}
