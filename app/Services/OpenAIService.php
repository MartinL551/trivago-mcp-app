<?php

namespace App\Services;

use OpenAI;

class OpenAIService
{
    private OpenAI\Client $client;
    private string $model;
    private const ROLE_CONTENT = 'Extract travel search parameters. Return ONLY raw JSON. Do not use markdown. Do not wrap the JSON in backticks. No explanation';

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

    public function extractSearchIntent(string $msg): array
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
                        'destination' => ['type' => 'string'],
                        'holiday_type' => ['type' => 'string'],
                        'budget' => ['type' => ['number', 'null']],
                    ],
                    'required' => ['destination', 'holiday_type', 'budget'],
                    'additionalProperties' => false,
                ],
            ],
        ],
        ]);

    

        return json_decode($response->outputText, true) ?? [];
    }
}

