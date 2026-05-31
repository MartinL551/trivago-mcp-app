<?php

namespace App\Services;

use App\Data\LlmData;
use App\Data\LlmScore;
use App\Models\Accommodation;
use Illuminate\Database\Eloquent\Collection;
use OpenAI;


class OpenAIService
{
    private OpenAI\Client $client;
    private string $model;
    private const ROLE_CONTENT_INTENT = 'Extract travel search parameters. Return ONLY raw JSON. Do not use markdown. Do not wrap the JSON in backticks. No explanation. arrival and departure MUST be after todays date';
    private const ROLE_CONTENT_SCORE = 'Extract score for accommodation signals based on the values passed. The Trivago_Id MUST be listed for each entry int the scoring.The signals to check will be the JSON keys and be a value of 0 to 100. 100 is a good match and 0 is a bad match. Return ONLY raw JSON. Do not use markdown. Do not wrap the JSON in backticks. No explanation. arrival and departure MUST be after todays date.  A short description of why these scores are given can be put in the why section';

    public function __construct() {
        $key = config('services.openai.key');
        $this->client = OpenAI::client($key);
        $this->model = config('services.openai.model');;
    }

    public function sendMessage(string $msg): string
    {
        $response = $this->client->responses()->create([
            'model' => $this->model,
            'input' => $msg,
        ]);

        return $response->outputText;
    }

    public function getScoreForAccommidations(Collection $accommodations, string $prompt): array
    {
        $payload = $accommodations->take(25)
            ->map(fn ($accommodation) => [
                    'trivago_id' => $accommodation->trivago_id,
                    'name' => $accommodation->name,
                    'location' => $accommodation->city,
                    'price_per_day' => $accommodation->price_per_day,
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
                    'content' => $this::ROLE_CONTENT_SCORE . ' Todays Date is: ' . now()->toString(),
                ],
                [
                    'role' => 'user',
                    'content' => [
                        [
                            'type' => 'input_text',
                            'text' => 'Score this accommodation.'
                        ],
                        [
                            'type' => 'input_text',
                            'text' => 'This was the orignal prompt. IMPORTANT USE THIS TO SCORE THE ACCOMMODATION AGAINST FOR A GIVEN SIGNAL 100 MEANS GOOD 0 MEANS BAD:' . $prompt
                        ],
                        [
                            'type' => 'input_text',
                            'text' => 'Budget signal is measured against the price_per_day in the JSON packet for that accommodation. try to esitmate a current conversion for each accomidation. Eg. if the promt is in pounds convert to euros first to compare'
                        ],
                        [
                            'type' => 'input_text',
                            'text' => 'Romance signal is measured against the location and avaliable amenities and any extra info in the name or description in the JSON packet for that accommodation'
                        ],
                        [
                            'type' => 'input_text',
                            'text' => 'Adventure signal is measured against the location and avaliable amenities and any extra info in the name or description in the JSON packet for that accommodation'
                        ],
                        [
                            'type' => 'input_text',
                            'text' => 'They scoring IS BASED ON COMPARIOSN TO OTHERS IN THE PAYLOAD AND ANY EXTRA KNOWLEDGE YOU HAVE OF THE AREA'
                        ],
                        [
                            'type' => 'input_text',
                            'text' => json_encode($payload)
                        ]
                    ],
                ]
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
                                        'budget' => ['type' => 'number'],
                                        'why' => ['type' => 'string'],
                                    ],
                                    'required' => [
                                        'trivago_id',
                                        'romance',
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

        foreach($decodedResponse['scores'] as $data) {
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
                    'content' => $this::ROLE_CONTENT_INTENT . ' Todays Date is: ' . now()->toString(),
                ],
                [
                    'role' => 'user',
                    'content' => $msg,
                ]
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
                                    'format' => 'date'
                                ],
                                'departure' => [
                                    'type' => 'string',
                                    'format' => 'date'
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
                                    'items' => ['type' => 'string']
                                ],
                                'budget' => ['type' => ['number', 'null']],
                                'amenities' => [
                                    'type' => 'array',
                                    'items' => ['type' => 'string']
                                ],
                            ],
                            'required' => ['arrival', 'departure', 'adults', 'children', 'rooms', 'children_ages', 'city', 'country', 'continent', 'holiday_type', 'budget', 'amenities'],
                            'additionalProperties' => false,
                        ],
                    ],
                ],
        ]);


        $decodedResponse = json_decode($response->outputText, true) ?? [];

        return new LlmData($decodedResponse);
    }
}

