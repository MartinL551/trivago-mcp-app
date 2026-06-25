<?php

namespace App\Services;

use App\Enums\PromptSignals;
use App\Models\SearchRequest;
use Illuminate\Support\Collection;
use App\Services\Concerns\Coordinates;

class AccommodationRankerService
{
    private SearchRequest $searchRequest; 
    private NominatimGeocodingService $geoCodingService;
    private DistanceService $distanceService;

    public function __construct(
        SearchRequest $searchRequest,
        DistanceService $distanceService
    )
    {
        $this->searchRequest = $searchRequest;
        $this->distanceService = $distanceService;
        $this->geoCodingService = new NominatimGeocodingService();
    }

    public function sortAccommodation(Collection $rows): Collection
    {
        return $rows->sortByDesc(self::sortForSignals($this->searchRequest->main_signal, $this->searchRequest->secondary_signal));
    }

    public function getSortedArray(Collection $rows): array
    {
        return self::sortAccommodation($rows)->all();
    }

    public function getSortedArrayAndTakeCount(Collection $rows, int $count): array
    {
        return self::sortAccommodation($rows)->take($count)->all();
    }

    private function sortForSignals(?string $mainSignal, ?string $secondarySignal): callable
    {
        return function ($accom) use ($mainSignal, $secondarySignal): float {
            return
                self::scoreForSignal($mainSignal, $accom) * 2 +
                self::scoreForSignal($secondarySignal, $accom);
        };
    }

    private function scoreForSignal(?string $signal, $accom): float
    {
        return match ($signal) {
            PromptSignals::Business->value => self::scoreBusiness($accom),
            PromptSignals::Budget->value => self::scoreBudget($accom),
            PromptSignals::Family->value => self::scoreFamily($accom),
            PromptSignals::Adventure->value => self::scoreAdventure($accom),
            PromptSignals::Romantic->value => self::scoreRomantic($accom),
            PromptSignals::Luxury->value => self::scoreLuxury($accom),
            default => 0,
        };
    }

    private function scoreBusiness($accom): float
    {
        // Convention here is 10 is most weighted and 1 is least
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

        $score = self::scoreForWantedAmenities($accom, $wantedAmenities, weight: 4);
        $score += self::scoreReviewRating($accom, weight: 5);
        $score += self::scoreRating($accom, weight: 2);
        $score += self::scoreReviewCount($accom, weight: 2);
        $score += self::scoreDistanceFromCentre($accom, weight: 5);
        $score += self::scoreBudgetRating($accom, weight: 5);

        return $score;
    }

    private function scoreLuxury($accom): float
    {
        // Convention here is 10 is most weighted and 1 is least
        $wantedAmenities = [
            'Spa' => 10,
            'Outdoor pool' => 9,
            'Restaurant' => 8,
            'Bar' => 7,
            'Gym' => 6,
            'Air conditioning' => 5,
            'Parking' => 3,
            'WiFi (room)' => 2,
            'WiFi in public areas' => 1,
        ];

        $score = self::scoreForWantedAmenities($accom, $wantedAmenities, weight: 5);
        $score += self::scoreReviewRating($accom, weight: 5);
        $score += self::scoreRating($accom, weight: 10);
        $score += self::scoreReviewCount($accom, weight: 2);
        $score += self::scoreDistanceFromCentre($accom, weight: 2);
        $score += self::scoreBudgetRating($accom, weight: 1);

        return $score;
    }

    private  function scoreFamily($accom): float
    {
        // Convention here is 10 is most weighted and 1 is least
        $wantedAmenities = [
            'Outdoor pool' => 10,
            'Parking' => 9,
            'Restaurant' => 8,
            'WiFi (room)' => 7,
            'WiFi in public areas' => 6,
            'Air conditioning' => 5,
            'Pet-friendly' => 4,
            'Gym' => 2,
            'Bar' => 1,
        ];

        $score = self::scoreForWantedAmenities($accom, $wantedAmenities, weight: 5);
        $score += self::scoreReviewRating($accom, weight: 5);
        $score += self::scoreRating($accom, weight: 3);
        $score += self::scoreReviewCount($accom, weight: 4);
        $score += self::scoreDistanceFromCentre($accom, weight: 5);
        $score += self::scoreBudgetRating($accom, weight: 4);

        return $score;
    }

    private  function scoreBudget($accom): float
    {
        // Convention here is 10 is most weighted and 1 is least
        $wantedAmenities = [
            'WiFi (room)' => 10,
            'Parking' => 8,
            'Air conditioning' => 7,
            'WiFi in public areas' => 6,
            'Restaurant' => 5,
            'Pet-friendly' => 4,
            'Bar' => 2,
            'Gym' => 1,
        ];

        $score = self::scoreForWantedAmenities($accom, $wantedAmenities, weight: 3);
        $score += self::scoreReviewRating($accom, weight: 4);
        $score += self::scoreRating($accom, weight: 1);
        $score += self::scoreReviewCount($accom, weight: 3);
        $score += self::scoreDistanceFromCentre($accom, weight: 2);
        $score += self::scoreBudgetRating($accom, weight: 8);

        return $score;
    }

    private  function scoreAdventure($accom): float
    {
        // Convention here is 10 is most weighted and 1 is least
        $wantedAmenities = [
            'Parking' => 10,
            'Pet-friendly' => 9,
            'WiFi (room)' => 7,
            'WiFi in public areas' => 6,
            'Restaurant' => 5,
            'Bar' => 4,
            'Air conditioning' => 3,
            'Gym' => 2,
            'Outdoor pool' => 1,
        ];

        $score = self::scoreForWantedAmenities($accom, $wantedAmenities, weight: 2);
        $score += self::scoreReviewRating($accom, weight: 4);
        $score += self::scoreRating($accom, weight: 2);
        $score += self::scoreReviewCount($accom, weight: 2);
        $score += self::scoreDistanceFromCentre($accom, weight: 1);
        $score += self::scoreBudgetRating($accom, weight: 4);

        return $score;
    }

    private  function scoreRomantic($accom): float
    {
        // Convention here is 10 is most weighted and 1 is least
        $wantedAmenities = [
            'Spa' => 10,
            'Outdoor pool' => 9,
            'Restaurant' => 8,
            'Bar' => 7,
            'Air conditioning' => 6,
            'WiFi (room)' => 4,
            'WiFi in public areas' => 3,
            'Gym' => 2,
            'Parking' => 1,
        ];

        $score = self::scoreForWantedAmenities($accom, $wantedAmenities, weight: 5);
        $score += self::scoreReviewRating($accom, weight: 5);
        $score += self::scoreRating($accom, weight: 4);
        $score += self::scoreReviewCount($accom, weight: 2);
        $score += self::scoreDistanceFromCentre($accom, weight: 3);
        $score += self::scoreBudgetRating($accom, weight: 1);

        return $score;
    }

    private  function scoreDistanceFromCentre($accom, float $weight): float
    {   
        if(!$accom['latitude'] || !$accom['longitude']) {
            return 0;
        }
        
        $maxUsefulDistanceKm = 20;

        $fromCoords = new Coordinates($this->searchRequest->latitude, $this->searchRequest->longitude);
        $toCoords = new Coordinates($accom['latitude'], $accom['longitude']);

        $distance = $this->distanceService->between($fromCoords, $toCoords);

        return  max(0, 100 - (($distance / $maxUsefulDistanceKm) * 100)) * $weight;
    }

    private  function scoreReviewRating($accom, int $weight = 1): float
    {
        return ($accom['review_rating'] ?? 0) * $weight;
    }

    private  function scoreRating($accom, int $weight = 1): float
    {
        return ($accom['hotel_rating'] ?? 0) * $weight;
    }

    private  function scoreReviewCount($accom, int $weight = 1): float
    {
        return ($accom['review_count'] ?? 0) * $weight;
    }

    private  function scoreBudgetRating($accom, int $weight = 1): float
    {
        $price = $accom['price_per_night'];

        if (! $price) {
            return 0;
        }

        return max(0, 100 - $price);
    }

    private  function scoreForWantedAmenities($accom, array $wantedAmenites, int $weight = 1): float
    {
        $totalScore = 0;

        foreach ($wantedAmenites as $amenity => $score) {
            if (self::hasAmenity($accom, $amenity)) {
                $totalScore += $score;
            }
        }

        return $totalScore * $weight;
    }

    private  function hasAmenity($accom, string $amenity): bool
    {
        return str_contains(strtolower($accom['amenites']), strtolower($amenity));
    }
}
