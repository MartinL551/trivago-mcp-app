<?php

namespace App\Data;

class McpSearchMapper
{
    public function __construct() {}

    public function toAccommodationPayload(LlmData $data): array
    {
        return [
            'query' => "{$data->city}, {$data->country}, {$data->continent}",
            'arrival' => $data->arrival->format('Y-m-d'),
            'departure' => $data->departure->format('Y-m-d'),
            'adults' => $data->adults,
            'children' => $data->children,
            'hotel_rating' => $this->getRatingsState($data->rating),
            'review_rating' =>  $this->getReveiewRatingsState($data->review_rating),
            'children_ages' => empty($data->childAges)
                ? ''
                : implode('-', $data->childAges),
            'rooms' => min($data->rooms, $data->adults),
        ];
    }

    private function getReveiewRatingsState(float $llmReviewRating): array 
    {
        $reviewRatings = [
            'rating70' => true,
        ];


        return $reviewRatings;
    }

    private function getRatingsState(array $llmRatings): array
    {
        $ratings = [
            '1star' => false,
            '2star' => false,
            '3star' => false,
            '4star' => false,
            '5star' => false,
        ];


        if(count($llmRatings) > 0) {
            foreach($llmRatings as $rating) {
                $ratings["{$rating}star"] = true;
            }
        } else {
            $keys = array_keys($ratings);
            $ratings = array_fill_keys($keys, true);
        }
        
        return $ratings;
    }
}
