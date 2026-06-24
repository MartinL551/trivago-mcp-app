<?php

namespace App\Services;

use App\Data\McpSearchMapper;
use App\Data\SearchIntentData;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class TrivagoMcpService
{
    private const BASE_URL = 'https://mcp.trivago.com/mcp';

    private const CACHE_KEY = 'trivago_mcp_session_id';

    private const ACCOMMODATION_SEARCH = 'trivago-accommodation-search';

    private const RADIUS_SEARCH = 'trivago-accommodation-radius-search';

    public function __construct(
        private McpSearchMapper $mcpSearchMapper
    ) {}

    private function getSessionId(): string
    {
        $sessionId = Cache::get($this::CACHE_KEY);

        if ($sessionId) {
            return $sessionId;
        }

        return $this->initSession();
    }

    private function initSession(): string
    {
        $headers = [
            'Accept' => 'application/json, text/event-stream',
            'Content-Type' => 'application/json',
        ];

        $jsonBody = [
            'jsonrpc' => '2.0',
            'id' => 1,
            'method' => 'initialize',
            'params' => [
                'protocolVersion' => '2025-03-26',
                'capabilities' => [],
                'clientInfo' => [
                    'name' => 'trivago-mcp-app',
                    'version' => '1.0.0',
                ],
            ],
        ];

        $response = $this->sendJson($headers, $jsonBody);

        $response->throw();

        $sessionId = $response->header('Mcp-Session-Id');

        if (!$sessionId) {
            throw new \RuntimeException('No MCP Session ID');
        }

        Cache::put($this::CACHE_KEY, $sessionId, now()->addMinutes(30));

        return $sessionId;
    }

    private function getResultsFromMcp(SearchIntentData $llmData, string $tool): array
    {
        $sessionId = $this->getSessionId();

        $headers = [
            'Accept' => 'application/json, text/event-stream',
            'Content-Type' => 'application/json',
            'Mcp-Session-Id' => $sessionId,
        ];

        $params = match ($tool) {
            TrivagoMcpService::ACCOMMODATION_SEARCH => $this->mcpSearchMapper->toAccommodationPayload($llmData),
            // TrivagoMcpService::RADIUS_SEARCH => TrivagoMcpService::RADIUS_SEARCH
        };

        Log::info('Trivago API Params', [
            'params' => $params,
        ]);

        $request = [
            'jsonrpc' => '2.0',
            'id' => (string) Str::uuid(),
            'method' => 'tools/call',
            'params' => [
                'name' => $tool,
                'arguments' => $params,
            ],
        ];

        $response = $this->sendJson($headers, $request) ?? null;

        $response->throw(function ($response, $e) {
            Log::error('Trivago API request failed', [
                'status' => $response->status(),
                'body' => $response->body(),
                'url' => $response->effectiveUri(),
            ]);
        });

        $results = $response->json() ?? [];

        Log::info('Trivago API raw response', [
            'tool' => $tool,
            'status' => $response->status(),
            'body' => $response->body(),
        ]);

        return $results;
    }

    public function getAccommodationSearch(SearchIntentData $llmData): array
    {
        $accommodations = [];

        $accommodationsFromSearch = $this->getResultsFromMcp($llmData, TrivagoMcpService::ACCOMMODATION_SEARCH)['result']['structuredContent']['accommodations'];

        foreach ($accommodationsFromSearch as $accommodation) {
            $accommodations[$accommodation['accommodation_id']] = $accommodation;
        }

        return $accommodations;
    }

    private function sendJson(array $headers, array $body): Response
    {
        return Http::connectTimeout(5)->timeout(20)->withHeaders($headers)->post($this::BASE_URL, $body);
    }
}
