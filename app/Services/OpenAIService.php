<?php

namespace App\Services;

use App\Data\LlmData;
use App\Data\LlmScore;
use App\Enums\PromptSignals;
use App\Models\SearchRequest;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use OpenAI;

class OpenAIService
{
    private OpenAI\Client $client;

    private string $model;

    private const ROLE_CONTENT_INTENT = 'Extract travel search parameters. Return ONLY raw JSON. Do not use markdown. Do not wrap the JSON in backticks. No explanation. arrival and departure MUST be after todays date.';

    private const ROLE_CONTENT_SCORE = 'Extract score for accommodation signals based on the values passed. The Trivago_Id MUST be listed for each entry int the scoring.The signals to check will be the JSON keys and be a value of 0 to 100. 100 is a good match and 0 is a bad match. Return ONLY raw JSON. Do not use markdown. Do not wrap the JSON in backticks. No explanation. arrival and departure MUST be after todays date.  A short description of why these scores are given can be put in the why section this will be end user facing so needs to be friendly to read';

    public function __construct()
    {
        $key = config('services.openai.key');
        $this->client = OpenAI::client($key);
        $this->model = config('services.openai.model');
    }

    public function sendMessage(string $msg): string
    {
        $response = $this->client->responses()->create([
            'model' => $this->model,
            'input' => $msg,
        ]);

        return $response->outputText;
    }

    public function getScoreForAccommidations(Collection $accommodations, SearchRequest $searchRequest): array
    {
        $prompt = $searchRequest->prompt;
        $payload = $accommodations->take(25)
            ->map(fn ($accommodation) => [
                'trivago_id' => $accommodation->trivago_id,
                'name' => $accommodation->name,
                'location' => $accommodation->city,
                'price_per_night' => $accommodation->price_per_night,
                'rating' => $accommodation->rating,
                'review_rating' => $accommodation->rating,
                'review_count' => $accommodation->rating,
                'amenities' => $accommodation->amenites,
                'description' => $accommodation->description,
            ])
            ->values()
            ->all();

        $response = $this->client->responses()->create([
            'model' => $this->model,
            'input' => [
                [
                    'role' => 'system',
                    'content' => $this::ROLE_CONTENT_SCORE.' Todays Date is: '.now()->toString(),
                ],
                [
                    'role' => 'user',
                    'content' => [
                        [
                            'type' => 'input_text',
                            'text' => 'Score this accommodation.',
                        ],
                        [
                            'type' => 'input_text',
                            'text' => 'This was the orignal prompt. IMPORTANT USE THIS TO SCORE THE ACCOMMODATION AGAINST FOR A GIVEN SIGNAL 100 MEANS GOOD 0 MEANS BAD:'.$prompt,
                        ],
                        [
                            'type' => 'input_text',
                            'text' => 'Budget signal is measured against the price_per_night in the JSON packet for that accommodation. try to esitmate a current conversion for each accommodation, Eg. if the promt is in pounds convert to euros first to compare',
                        ],
                        [
                            'type' => 'input_text',
                            'text' => 'Romance signal is measured against the location and avaliable amenities and any extra info in the name or description in the JSON packet for that accommodation',
                        ],
                        [
                            'type' => 'input_text',
                            'text' => 'Adventure signal is measured against the location and avaliable amenities and any extra info in the name or description in the JSON packet for that accommodation',
                        ],
                        [
                            'type' => 'input_text',
                            'text' => 'Family signal is measured against the location and avaliable amenities and any extra info in the name or description in the JSON packet for that accommodation',
                        ],
                        [
                            'type' => 'input_text',
                            'text' => 'Business signal is measured against the location and avaliable amenities and any extra info in the name or description in the JSON packet for that accommodation',
                        ],
                        [
                            'type' => 'input_text',
                            'text' => 'Luxury signal is measured against the location and avaliable amenities and any extra info in the name or description in the JSON packet for that accommodation',
                        ],
                        [
                            'type' => 'input_text',
                            'text' => 'The scoring IS BASED ON COMPARIOSN TO OTHERS IN THE PAYLOAD AND ANY EXTRA KNOWLEDGE YOU HAVE OF THE LOCATION. E.g London is good for business and luxury. A small town in the alps is good for adventure',
                        ],
                        [
                            'type' => 'input_text',
                            'text' => 'rating in the payload is how many stars it has. review_rating is how users score the hotel on trivagos website',
                        ],
                        [
                            'type' => 'input_text',
                            'text' => json_encode($payload),
                        ],
                        [
                            'type' => 'input_text',
                            'text' => 'These are the the signals for the main_signal and secondary_signal fields. This is used to help filter results down on the search request. main_signal IS the primary meaning of the prompt. secondary_signal IS the secondary meaning of the prompt. This CAN be used to help guide the scoring: '.json_encode(array_column(PromptSignals::cases(), 'value')),
                        ],
                    ],
                ],
            ],
            'text' => [
                'format' => [
                    'type' => 'json_schema',
                    'name' => 'accommodation_scores',
                    'schema' => [
                        'type' => 'object',
                        'properties' => [
                            'scores' => [
                                'type' => 'array',
                                'items' => [
                                    'type' => 'object',
                                    'properties' => [
                                        'trivago_id' => ['type' => 'string'],
                                        'romance' => ['type' => 'number'],
                                        'adventure' => ['type' => 'number'],
                                        'luxury' => ['type' => 'number'],
                                        'family' => ['type' => 'number'],
                                        'business' => ['type' => 'number'],
                                        'budget' => ['type' => 'number'],
                                        'why' => ['type' => 'string'],
                                    ],
                                    'required' => [
                                        'trivago_id',
                                        'romance',
                                        'luxury',
                                        'business',
                                        'family',
                                        'adventure',
                                        'budget',
                                        'why',
                                    ],
                                    'additionalProperties' => false,
                                ],
                            ],
                        ],
                        'required' => ['scores'],
                        'additionalProperties' => false,
                    ],
                ],
            ],
        ]);

        $decodedResponse = json_decode($response->outputText, true) ?? [];
        $scores = [];

        foreach ($decodedResponse['scores'] as $data) {
            $scores[] = new LlmScore($data);
        }

        return $scores;
    }

    public function extractSearchIntent(string $msg): LlmData
    {
        $response = $this->client->responses()->create([
            'model' => $this->model,
            'input' => [
                [
                    'role' => 'system',
                    'content' => $this::ROLE_CONTENT_INTENT.' Todays Date is: '.now()->toString(),
                ],
                [
                    'role' => 'user',
                    'content' => [
                        [
                            'type' => 'input_text',
                            'text' => 'Prompt: '.$msg,
                        ],
                        [
                            'type' => 'input_text',
                            'text' => 'Allowed signals: '.json_encode(array_column(PromptSignals::cases(), 'value')).'. Select the signal that best represents the PRIMARY intent of the prompt as main_signal. Select a secondary_signal ONLY if the prompt clearly expresses a second distinct intent. If there is no clear secondary intent, return null.',
                        ],
                        [
                            'type' => 'input_text',
                            'text' => 'Budget should only be selected if the user explicitly mentions budget, cheap, affordable, low-cost, value, price limits, or similar cost-sensitive language.',
                        ],
                        [
                            'type' => 'input_text',
                            'text' => 'IMPORTANT: these are the known amenites that trivago MCP accepts. WiFi in public areas, WiFi (room), Outdoor pool, Spa, Parking, Pet-friendly, Air conditioning, Restaurant, Bar, Gym',
                        ],
                        [
                            'type' => 'input_text',
                            'text' => 'rating is how many stars (e.g. a 5 star hotel and 4 star hotel) needed. This should be given as an array of needed ratings e.g [5,4,3,2,1]. review_rating is how users score the hotel on trivagos website this is a value from 1 to 10. The rating put here should be based on the user prompt give. E.g. luxury wants 5 and 4 star hotels, or family as 3. But IMPORTANT this is based on the context of the prompt Luxury familt might be in the middle for example you can use the main_signal and secondary_signal to help with this',
                        ],
                        [
                            'type' => 'input_text',
                            'text' => 'The room count should be generated from the number of adults and children. With rooms being <= the number of adults. But with the goal of 3 people per room max',
                        ],
                        [
                            'type' => 'input_text',
                            'text' => 'Examples: "Luxury hotel for a romantic holiday" => main_signal=luxury, secondary_signal=romantic. "Cheap hotel in London" => main_signal=budget, secondary_signal=null. "Business hotel with good wifi" => main_signal=business, secondary_signal=null.',
                        ],
                    ],
                ],
            ],
            'text' => [
                'format' => [
                    'type' => 'json_schema',
                    'name' => 'travel_search',
                    'schema' => [
                        'type' => 'object',
                        'properties' => [
                            'arrival' => [
                                'type' => 'string',
                                'format' => 'date',
                            ],
                            'departure' => [
                                'type' => 'string',
                                'format' => 'date',
                            ],
                            'adults' => ['type' => 'number'],
                            'children' => ['type' => 'number'],
                            'rooms' => ['type' => 'number'],
                            'children_ages' => [
                                'type' => 'array',
                                'items' => ['type' => 'number'],
                            ],
                            'city' => ['type' => 'string'],
                            'country' => ['type' => 'string'],
                            'continent' => ['type' => 'string'],
                            'holiday_type' => [
                                'type' => 'array',
                                'items' => ['type' => 'string'],
                            ],
                            'budget' => ['type' => ['number', 'null']],
                            'amenities' => [
                                'type' => 'array',
                                'items' => ['type' => 'string'],
                            ],
                            'main_signal' => ['type' => 'string'],
                            'secondary_signal' => ['type' => 'string'],
                            'rating' => [
                                'type' => 'array',
                                'items' => ['type' => 'number'],
                            ],
                            'review_rating' => ['type' => 'number'],
                        ],
                        'required' => [
                            'arrival', 
                            'departure', 
                            'adults', 
                            'children', 
                            'rooms', 
                            'children_ages', 
                            'city', 
                            'country', 
                            'continent', 
                            'holiday_type', 
                            'budget', 
                            'amenities', 
                            'main_signal', 
                            'secondary_signal',
                            'rating',
                            'review_rating',
                        ],
                        'additionalProperties' => false,
                    ],
                ],
            ],
        ]);

        $decodedResponse = json_decode($response->outputText, true) ?? [];

        return new LlmData($decodedResponse);
    }
}
