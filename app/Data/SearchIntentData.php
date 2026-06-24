<?php

namespace App\Data;

use Carbon\Carbon;

class SearchIntentData
{
    public Carbon $arrival;

    public Carbon $departure;

    public int $adults;

    public int $children;

    public int $rooms;

    public array $childAges;

    public string $city;

    public string $country;

    public string $continent;

    public array $holidayType;

    public float $budget;

    public array $amenities;

    public string $status;

    public string $mainSignal;

    public string $secondarySignal;

    public array $rating;

    public int $review_rating;

    public string $landmark;

    public ?string $currency;

    /**
     * @param  array  $searchResponse Raw structured search intent output
     */
    public function __construct(array $searchResponse)
    {
        $this->arrival = $searchResponse['arrival'] ?? null ? Carbon::parse($searchResponse['arrival']) : null;
        $this->departure = $searchResponse['departure'] ?? null ? Carbon::parse($searchResponse['departure']) : null;
        $this->adults = $searchResponse['adults'] ?? 2;
        $this->children = $searchResponse['children'] ?? 1;
        $this->rooms = $searchResponse['rooms'] ?? 0;
        $this->childAges = $searchResponse['children_ages'] ?? [];
        $this->city = $searchResponse['city'] ?? '';
        $this->country = $searchResponse['country'] ?? '';
        $this->continent = $searchResponse['continent'] ?? '';
        $this->landmark = $searchResponse['landmark'] ?? '';
        $this->holidayType = $searchResponse['holiday_type'] ?? [];
        $this->budget = (float) $searchResponse['budget'] ?? 0;
        $this->amenities = $searchResponse['amenities'] ?? [];
        $this->status = count($searchResponse) === 0 ? 'failed' : 'success';
        $this->mainSignal = $searchResponse['main_signal'] ?? null;
        $this->secondarySignal = $searchResponse['secondary_signal'] ?? null;
        $this->rating = $searchResponse['rating'] ?? [];
        $this->review_rating = $searchResponse['review_rating'] ?? null;
        $this->currency = null;
    }
}
