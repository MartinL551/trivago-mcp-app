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
    private const ROLE_CONTENT_SCORE = 'Extract score for accommodation signals based on the values passed. The Trivago_Id MUST be listed for each entry int the scoring.The signals to check will be the JSON keys and be a value of 0 to 100. 100 is a good match and 0 is a bad match. Return ONLY raw JSON. Do not use markdown. Do not wrap the JSON in backticks. No explanation. arrival and departure MUST be after todays date';

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

    public function getScoreForAccommidation(Accommodation $accommodation): LlmScore
    {
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
                            'text' => json_encode([
                                'name' => $accommodation->name,
                                'location' => $accommodation->city,
                                'price_per_night' => $accommodation->price_per_night,
                                'amenities' => $accommodation->amenites,
                                'description' => $accommodation->description,
                            ])
                        ]
                    ],
                ]
            ],
            'text' => [
                'format' => [
                        'type' => 'json_schema',
                        'name' => 'accommodation_score',
                        'schema' => [
                            'type' => 'object',
                            'properties' => [
                                'romance' => ['type' => 'number'],
                                'adventure' => ['type' => 'number'],
                                'budget' => ['type' => 'number'],
                            ],
                            'required' => ['romance', 'adventure', 'budget'],
                            'additionalProperties' => false,
                        ],
                    ],
                ],
        ]);


        $decodedResponse = json_decode($response->outputText, true) ?? [];

        return new LlmScore($decodedResponse);
    }


      public function getScoreForAccommidations(Collection $accommodations): LlmScore
    {
        $payload = $accommodations->take(25)
            ->map(fn ($accommodation) => [
                    'trivago_id' => $accommodation->trivago_id,
                    'name' => $accommodation->name,
                    'location' => $accommodation->city,
                    'price_per_night' => $accommodation->price_per_night,
                    'amenities' => $accommodation->amenites,
                    'description' => $accommodation->description,
            ])
            ->values()
            ->all();

            $start = microtime(true);

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
                                    ],
                                    'required' => [
                                        'trivago_id',
                                        'romance',
                                        'adventure',
                                        'budget',
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

        

        logger()->info('LLM Request, get score', [
            'duration_ms' => round((microtime(true) - $start) * 1000),
            'status' => $response->status,
        ]);


        $decodedResponse = json_decode($response->outputText, true) ?? [];

        dd($decodedResponse);

        return new LlmScore($decodedResponse);
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
                                'destination' => ['type' => 'string'],
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
                            'required' => ['arrival', 'departure', 'adults', 'children', 'rooms', 'children_ages', 'destination', 'holiday_type', 'budget', 'amenities'],
                            'additionalProperties' => false,
                        ],
                    ],
                ],
        ]);


        $decodedResponse = json_decode($response->outputText, true) ?? [];

        return new LlmData($decodedResponse);
    }
}

