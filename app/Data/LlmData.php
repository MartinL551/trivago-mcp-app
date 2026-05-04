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
    public string $status;

    /**
     * @param array $llmResponse Raw structured output from LLM
     */
    public function __construct(array $llmResponse)
    {
        $this->arrival = $llmResponse['arrival'] ?? null ? Carbon::parse($llmResponse['arrival']) : null;
        $this->departure = $llmResponse['departure'] ?? null ? Carbon::parse($llmResponse['departure']) : null;
        $this->adults = $llmResponse['adults'] ?? 2;
        $this->children = $llmResponse['children'] ?? 1;
        $this->rooms = $llmResponse['rooms'] ?? 0;
        $this->childAges = $llmResponse['children_ages'] ?? [];
        $this->destination = $llmResponse['destination'] ?? '';
        $this->holidayType = $llmResponse['holiday_type'] ?? [];
        $this->budget = (float) $llmResponse['budget'] ?? 0;
        $this->amenities = $llmResponse['amenities'] ?? [];
        $this->status = count($llmResponse) === 0 ? "failed" : "success";
    }
}