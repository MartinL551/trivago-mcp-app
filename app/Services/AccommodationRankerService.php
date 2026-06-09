<?php

namespace App\Services;

use App\Enums\PromptSignals;
use App\Models\SearchRequest;
use Illuminate\Support\Collection;

class AccommodationRankerService
{
    public static function sortAccommodation(Collection $rows, SearchRequest $searchRequest): Collection
    {
        return $rows->sortByDesc(self::sortForSignals($searchRequest->main_signal, $searchRequest->secondary_signal));
    }

    public static function getSortedArray(Collection $rows, SearchRequest $searchRequest): array
    {
        return self::sortAccommodation($rows, $searchRequest)->all();
    }

    public static function getSortedArrayAndTakeCount(Collection $rows, SearchRequest $searchRequest, int $count): array
    {
        return self::sortAccommodation($rows, $searchRequest)->take($count)->all();
    }

    private static function sortForSignals(?string $mainSignal, ?string $secondarySignal): callable
    {
        return function ($accom) use ($mainSignal, $secondarySignal): float {
            return
                self::scoreForSignal($mainSignal, $accom) * 2 +
                self::scoreForSignal($secondarySignal, $accom);
        };
    }

    private static function scoreForSignal(?string $signal, $accom): float
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

    private static function scoreBusiness($accom): float
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

    private static function scoreLuxury($accom): float
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
        $score = self::scoreRating($accom, weight: 10);
        $score += self::scoreReviewCount($accom, weight: 2);
        $score += self::scoreDistanceFromCentre($accom, weight: 2);
        $score += self::scoreBudgetRating($accom, weight: 1);

        return $score;
    }

    private static function scoreFamily($accom): float
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
        $score += self::scoreDistanceFromCentre($accom, weight: 3);
        $score += self::scoreBudgetRating($accom, weight: 4);

        return $score;
    }

    private static function scoreBudget($accom): float
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

    private static function scoreAdventure($accom): float
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

    private static function scoreRomantic($accom): float
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

    private static function scoreDistanceFromCentre($accom, float $weight): float
    {
        $distanceKm = self::distanceToKm(
            $accom['distance_to_centre'] ?? null,
            $accom['distance_unit'] ?? 'km',
        );

        if ($distanceKm === null) {
            return 0;
        }

        $maxUsefulDistanceKm = 20;

        $normalisedScore = max(
            0,
            1 - ($distanceKm / $maxUsefulDistanceKm)
        );

        return $normalisedScore * 10 * $weight;
    }

    private static function distanceToKm(?float $distance, string $unit): ?float
    {
        if ($distance === null) {
            return null;
        }

        return match (strtolower($unit)) {
            'km', 'kilometres', 'kilometers' => $distance,
            'mi', 'mile', 'miles' => $distance * 1.60934,
            default => null,
        };
    }

    private static function scoreReviewRating($accom, int $weight = 1): float
    {
        return ($accom['review_rating'] ?? 0) * $weight;
    }

    private static function scoreRating($accom, int $weight = 1): float
    {
        return ($accom['rating'] ?? 0) * $weight;
    }

    private static function scoreReviewCount($accom, int $weight = 1): float
    {
        return ($accom['review_count'] ?? 0) * $weight;
    }

    private static function scoreBudgetRating($accom, int $weight = 1): float
    {
        $price = $accom['price_per_day'];

        if (! $price) {
            return 0;
        }

        return max(0, 100 - $price);
    }

    private static function scoreForWantedAmenities($accom, array $wantedAmenites, int $weight = 1): float
    {
        $totalScore = 0;

        foreach ($wantedAmenites as $amenity => $score) {
            if (self::hasAmenity($accom, $amenity)) {
                $totalScore += $score;
            }
        }

        return $totalScore * $weight;
    }

    private static function hasAmenity($accom, string $amenity): bool
    {
        return str_contains(strtolower($accom['amenites']), strtolower($amenity));
    }
}
