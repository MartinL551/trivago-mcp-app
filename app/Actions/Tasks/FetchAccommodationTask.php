<?php

namespace app\Actions\Tasks;

use App\Data\LlmData;
use App\Enums\PromptSignals;
use App\Models\SearchRequest;
use App\Models\Suggestion;
use App\Models\Accommodation;
use App\Services\TrivagoMcpService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;

class FetchAccommodationTask
{
    public function __construct(
        private TrivagoMcpService $mcpSerivce,
    ) {}


    public function handle(SearchRequest $searchRequest, Suggestion $suggestion, LlmData $intent): ?Collection
    {
        $accomomdations = $this->mcpSerivce->getAccommodationSearchForSuggestion($intent, $suggestion);

        $rows = collect($accomomdations)->map(fn ($accom)  => [
            'trivago_id' => $accom['accommodation_id'],
            'name' => $accom['accommodation_name'],
            'postcode' => $accom['postal_code'],
            'address' => $accom['address'],
            'currency' => $accom['currency'],
            'price_per_stay' => $this->parsePrice($accom['price_per_stay']),
            'price_per_day' => $this->parsePrice($accom['price_per_night']),
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
        ]);

        $rowValues = $rows->sortByDesc($this->sortForSignals($searchRequest->main_signal, $searchRequest->secondary_signal))->take(5)->all();

        Accommodation::upsert(
            $rowValues,
            ['trivago_id'],
            [
                'name', 
                'postcode', 
                'address',
                'currency', 
                'price_per_stay', 
                'price_per_day', 
                'rating', 
                'city',
                'review_rating',
                'review_count',
                'amenites',
                'trivago_url',
                'trivago_image_url',
                'distance_string', 
                'distance_to_center',
                'distance_units',
                'desc',
            ]
        );

        $insertedAccoms = Accommodation::whereIn('trivago_id', collect($rows)->pluck('trivago_id'))->latest()->get();

        if(count($insertedAccoms) > 0){
            $searchRequest->accommodations()->syncWithoutDetaching($insertedAccoms);
        } else {
            return null;
        }

        return $insertedAccoms;
    }

    private function sortForSignals(?string $mainSignal, ?string $secondarySignal): callable
    {
        return function ($accom) use ($mainSignal, $secondarySignal): float {
            return
                $this->scoreForSignal($mainSignal, $accom) * 2 +
                $this->scoreForSignal($secondarySignal, $accom);
        };
    }

    private function scoreForSignal(?string $signal, $accom): float
    {
        return match ($signal) {
            PromptSignals::Business->value => $this->scoreBusiness($accom),
            PromptSignals::Budget->value => $this->scoreBudget($accom),
            PromptSignals::Family->value => $this->scoreFamily($accom),
            PromptSignals::Adventure->value => $this->scoreAdventure($accom),
            PromptSignals::Romantic->value => $this->scoreRomantic($accom),
            PromptSignals::Luxury->value => $this->scoreLuxury($accom),
            default => 0,
        };
    }

    private function scoreBusiness($accom): float
    {
        $wantedAmenities = [
            'WiFi (room)' => 10,
            'WiFi in public areas' => 9,
            'Air conditioning' => 8,
            'Parking' => 7,
            'Restaurant' => 6,
            'Gym' => 5,
            'Bar' => 4,
            'Pet-friendly' => 1,
        ];

        $amenitesScore = $this->scoreForWantedAmenites($accom, $wantedAmenities);

        return
            $amenitesScore * 3 +
            (($accom['review_rating'] ?? 0) * 3) +
            (($accom['rating'] ?? 0) * 2) +
            min(($accom['review_count'] ?? 0), 1000) / 100;
    }

    private function scoreBudget($accom): float
    {
        $price = $accom['price_per_day'];

        if (!$price) {
            return 0;
        }

        return max(0, 100 - $price);
    }

    private function scoreFamily($accom): float 
    {
        $wantedAmenities = [
            'Outdoor pool' => 10,
            'Restaurant' => 8,
            'Parking' => 7,
            'WiFi (room)' => 5,
            'Air conditioning' => 5,
            'Pet-friendly' => 3,
            'Gym' => 1,
        ];
        $amenitesScore = $this->scoreForWantedAmenites($accom, $wantedAmenities);

        return $amenitesScore + 0;
    }

     private function scoreAdventure($accom): float 
    {
        $wantedAmenities = [
            'Parking' => 10,
            'WiFi (room)' => 8,
            'Restaurant' => 6,
            'Air conditioning' => 5,
            'Gym' => 4,
            'Bar' => 3,
            'Pet-friendly' => 2,
        ];

        return 0;
    }
     
    private function scoreRomantic($accom): float 
    {
        $wantedAmenities = [
            'Spa' => 10,
            'Bar' => 7,
            'Restaurant' => 7,
            'Outdoor pool' => 6,
            'WiFi (room)' => 1,
        ];

        return 0;
    }

    private function scoreLuxury($accom): float
    {
        $wantedAmenities = [
            'Spa' => 10,
            'Outdoor pool' => 9,
            'Restaurant' => 8,
            'Bar' => 7,
            'Air conditioning' => 6,
            'Gym' => 5,
            'WiFi (room)' => 3,
            'Parking' => 2,
        ];

        $amenitesScore = $this->scoreForWantedAmenites($accom, $wantedAmenities);

        return
            $amenitesScore * 4 +
            (($accom['review_rating'] ?? 0) * 3) +
            (($accom['rating'] ?? 0) * 4) +
            min(($accom['review_count'] ?? 0), 1000) / 100;
    }

    private function scoreForWantedAmenites($accom, array $wantedAmenites): float
    {
        $totalScore = 0; 

        foreach($wantedAmenites as $amenity => $score){
            if($this->hasAmenity($accom, $amenity)){
                $totalScore += $score;
            }
        }

        return $totalScore;
    }

    private function hasAmenity($accom, string $amenity): bool
    {
        return str_contains(strtolower($accom['amenites']), strtolower($amenity));
    }
    
    private function parsePrice(?string $price): float
    {
        if (!$price) {
            return 0;
        }

        return (float) preg_replace('/[^\d.]/', '', $price);
    }
}