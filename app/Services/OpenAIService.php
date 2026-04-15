<?php

namespace App\Services;

use App\Data\LlmData;
use OpenAI;

class OpenAIService
{
    private OpenAI\Client $client;
    private string $model;
    private const ROLE_CONTENT = 'Extract travel search parameters. Return ONLY raw JSON. Do not use markdown. Do not wrap the JSON in backticks. No explanation. arrival and departure MUST be after todays date. We are in year 2026';

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

    public function extractSearchIntent(string $msg): LlmData
    {
        $response = $this->client->responses()->create([
            'model' => $this->model,
            'input' => [
                [
                    'role' => 'system',
                    'content' => $this::ROLE_CONTENT,
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

