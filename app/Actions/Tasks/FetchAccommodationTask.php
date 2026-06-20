<?php

namespace app\Actions\Tasks;
use App\Data\LlmData;
use App\Models\Accommodation;
use App\Models\SearchRequest;
use App\Services\AccommodationRankerService;
use App\Services\DistanceService;
use App\Services\TrivagoMcpService;
use Illuminate\Database\Eloquent\Collection;

class FetchAccommodationTask
{
    public function __construct(
        private TrivagoMcpService $mcpSerivce,
    ) {}

    public function handle(SearchRequest $searchRequest, LlmData $intent): ?Collection
    {
        $accomomdations = $this->mcpSerivce->getAccommodationSearch($intent);

        $rows = collect($accomomdations)->map(fn ($accom) => [
            'trivago_id' => $accom['accommodation_id'],
            'search_request_id' => $searchRequest->id,

            'name' => $accom['accommodation_name'],

            'currency' => $accom['currency'],

            'price_per_stay' => $this->parsePrice($accom['price_per_stay']),
            'price_per_night' => $this->parsePrice($accom['price_per_night']),

            'hotel_rating' => $accom['hotel_rating'],

            'city' => $accom['country_city'],

            'review_rating' => (float) $accom['review_rating'],
            'review_count' => (int) preg_replace('/[^0-9]/', '', $accom['review_count']),

            'amenites' => $accom['top_amenities'],

            'trivago_url' => $accom['accommodation_url'],
            'trivago_image_url' => $accom['main_image'],

            'distance_string' => $accom['distance'] ?? null,

            'latitude' => $accom['latitude'] ?? null,
            'longitude' => $accom['longitude'] ?? null,

            'arrival' => $accom['arrival'] ?? null,
            'departure' => $accom['departure'] ?? null,

            'advertiser' => $accom['advertisers'] ?? null,
        ]);

        $distance = new DistanceService();
        $ranker = new AccommodationRankerService($searchRequest, $distance);
  

        $rowValues = $ranker->getSortedArrayAndTakeCount($rows, 5);

        Accommodation::upsert(
            $rowValues,
            ['trivago_id', 'search_request_id'],
            [
                'name',
                'currency',
                'price_per_stay',
                'price_per_night',
                'hotel_rating',
                'city',
                'review_rating',
                'review_count',
                'amenites',
                'trivago_url',
                'trivago_image_url',
                'distance_string',
                'latitude',
                'longitude',
                'arrival',
                'departure',
                'advertiser',
            ]
        );

        $insertedAccoms = Accommodation::whereIn('trivago_id', collect($rows)->pluck('trivago_id'))->latest()->get();

        if (count($insertedAccoms) < 0) {
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
