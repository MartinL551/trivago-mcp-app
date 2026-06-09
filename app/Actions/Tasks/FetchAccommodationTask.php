<?php

namespace app\Actions\Tasks;

use App\Data\LlmData;
use App\Models\Accommodation;
use App\Models\SearchRequest;
use App\Models\Suggestion;
use App\Services\AccommodationRankerService;
use App\Services\TrivagoMcpService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class FetchAccommodationTask
{
    public function __construct(
        private TrivagoMcpService $mcpSerivce,
        private AccommodationRankerService $rankerService,
    ) {}

    public function handle(SearchRequest $searchRequest, Suggestion $suggestion, LlmData $intent): ?Collection
    {

        Log::info('Fetching accommodations', [
            'search_request_id' => $searchRequest->id,
            'llm_data' => $intent,
        ]);

        $accomomdations = $this->mcpSerivce->getAccommodationSearchForSuggestion($intent, $suggestion);

        $rows = collect($accomomdations)->map(fn ($accom) => [
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

        $rowValues = $this->rankerService::getSortedArrayAndTakeCount($rows, $searchRequest, 5);

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

        if (count($insertedAccoms) > 0) {
            $searchRequest->accommodations()->syncWithoutDetaching($insertedAccoms);
        } else {
            return null;
        }

        return $insertedAccoms;
    }

    private function parsePrice(?string $price): float
    {
        if (! $price) {
            return 0;
        }

        return (float) preg_replace('/[^\d.]/', '', $price);
    }
}
