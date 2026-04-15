<?php

namespace App\Data;

use Carbon\Carbon;

class LlmData {
    public Carbon $arrival;
    public Carbon $departure;
    public int $adults;
    public int $children;
    public int $rooms;
    public array $childAges;
    public string $destination;
    public array $holidayType;
    public float $budget;
    public array $amenities;

    public function __construct(array $rawLlmValues)
    {
        $this->arrival = $rawLlmValues['arrival'] ? Carbon::createFromTimeString($rawLlmValues['arrival']) : Carbon::now();
        $this->departure = $rawLlmValues['departure'] ? Carbon::createFromTimeString($rawLlmValues['departure']) : Carbon::now();
        $this->adults = $rawLlmValues['adults'] ?? 0;
        $this->children = $rawLlmValues['children'] ?? 0;
        $this->rooms = $rawLlmValues['rooms'] ?? 0;
        $this->childAges = $rawLlmValues['children_ages'] ?? [];
        $this->destination = $rawLlmValues['destination'] ?? [];
        $this->holidayType = $rawLlmValues['holiday_type'] ?? [];
        $this->budget = $rawLlmValues['budget'] ?? 0;
        $this->amenities = $rawLlmValues['amenities'] ?? [];
    }
}