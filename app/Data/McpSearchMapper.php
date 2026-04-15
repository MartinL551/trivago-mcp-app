<?php

namespace App\Data;

class McpSearchMapper
{
    public function __construct()
    {
    }

    public function toSuggestionsPayload(LlmData $data): array
    {
        return [
            'query' => $data->destination,
        ];
    }

    public function toAccommodationPayload(LlmData $data, int $ns, int $id): array
    {
        return [
            'ns' => $ns,
            'id' => $id,
            'arrival' => $data->arrival->format('Y-m-d'),
            'departure' => $data->departure->format('Y-m-d'),
            'adults' => $data->adults,
            'children' => $data->children,
            'children_ages' => empty($data->childAges)
                ? ''
                : implode('-', $data->childAges),
            'rooms' => min($data->rooms, $data->adults),
           
        ];
    }
}
