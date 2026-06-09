<?php

namespace App\Data;

class McpSearchMapper
{
    public function __construct() {}

    public function toSuggestionsPayload(LlmData $data): array
    {
        return [
            'query' => "{$data->city}, {$data->country}, {$data->continent}",
        ];
    }

    public function toAccommodationPayload(LlmData $data, int $ns, int $id): array
    {
        $ratings = [
            '1star' => false,
            '2star' => false,
            '3star' => false,
            '4star' => false,
            '5star' => false,
        ];

        $reviewRatings = [
            'rating70' => true,
            'rating75' => true,
            'rating80' => true,
            'rating85' => true,
        ];

        if(count($data->rating) > 0) {
            foreach($data->rating as $rating) {
                $ratings["{$rating}star"] = true;
            }
        } else {
            $keys = array_keys($ratings);
            $ratings = array_fill_keys($keys, true);
        }
     
        return [
            'ns' => $ns,
            'id' => $id,
            'arrival' => $data->arrival->format('Y-m-d'),
            'departure' => $data->departure->format('Y-m-d'),
            'adults' => $data->adults,
            'children' => $data->children,
            'rating' => $ratings,
            'review_rating' =>  $reviewRatings,
            'children_ages' => empty($data->childAges)
                ? ''
                : implode('-', $data->childAges),
            'rooms' => min($data->rooms, $data->adults),
        ];
    }
}
