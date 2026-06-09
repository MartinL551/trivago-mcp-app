<?php

namespace App\Services;

use App\Data\LlmData;
use App\Data\McpSearchMapper;
use App\Models\Suggestion;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class TrivagoMcpService
{
    private const BASE_URL = 'https://mcp.trivago.com/mcp';
    private const CACHE_KEY = 'trivago_mcp_session_id';
    private const SUGGEST = 'trivago-search-suggestions';
    private const ACCOMMODATION_SEARCH = 'trivago-accommodation-search';
    private const RADIUS_SEARCH = 'trivago-accommodation-radius-search';

    public function __construct(
        private McpSearchMapper $mcpSearchMapper
    ) {
    }

    private function getSessionId(): string {
        $sessionId = Cache::get($this::CACHE_KEY);

        if($sessionId) {
            return $sessionId;
        }

        return $this->initSession();
    }


    private function initSession(): string {
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

        if(!$sessionId){
            throw new \RuntimeException('No MCP Session ID');
        }

        Cache::put($this::CACHE_KEY, $sessionId, now()->addMinutes(30));

        return $sessionId;
    }
    

    private function getResultsFromMcp(LlmData $llmData, string $tool, ?int $id = null, ?int $ns = null): array {
        $sessionId = $this->getSessionId();

        $headers = [
            'Accept' => 'application/json, text/event-stream',
            'Content-Type' => 'application/json',
            'Mcp-Session-Id' => $sessionId,
        ];


       $params = match($tool) {
            TrivagoMcpService::SUGGEST => $this->mcpSearchMapper->toSuggestionsPayload($llmData),
            TrivagoMcpService::ACCOMMODATION_SEARCH  => $this->mcpSearchMapper->toAccommodationPayload($llmData, $ns, $id),
            // TrivagoMcpService::RADIUS_SEARCH => TrivagoMcpService::RADIUS_SEARCH
        };

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

        return $response->json() ?? [];
    }

    public function getSuggestions(LlmData $llmData): array {
        return $this->getResultsFromMcp($llmData, TrivagoMcpService::SUGGEST)['result']['structuredContent']['suggestions'];
    }

    public function getAccommodationSearch(LlmData $llmData): array {
        $suggestions = $this->getSuggestions($llmData);

        $accommodations = [];

        $collection = collect($suggestions)->first();

        $ns = $collection['ns'];
        $id = $collection['id'];

        $accommodationsForSuggestion = $this->getResultsFromMcp($llmData, TrivagoMcpService::ACCOMMODATION_SEARCH, $id, $ns)['result']['structuredContent']['accommodations'];
       
        foreach($accommodationsForSuggestion as $accommodation){
            $accommodations[$accommodation['accommodation_id']] = $accommodation;
        }

        return $accommodations;
    }

     public function getAccommodationSearchForSuggestion(LlmData $llmData, Suggestion $suggestion): array {
        $accommodations = [];

        $ns = $suggestion->trivago_ns;
        $id = $suggestion->trivago_id;

        $response = $this->getResultsFromMcp($llmData, TrivagoMcpService::ACCOMMODATION_SEARCH, $id, $ns);

        if(!array_key_exists('accommodations', $response['result']['structuredContent'])) {
            return $accommodations;
        }

        $accommodationsForSuggestion = $response['result']['structuredContent']['accommodations'];
       
        foreach($accommodationsForSuggestion as $accommodation){
            $accommodations[$accommodation['accommodation_id']] = $accommodation;
        }

        return $accommodations;
    }

    private function sendJson(array $headers, array $body): \Illuminate\Http\Client\Response
    {
        return Http::connectTimeout(5)->timeout(20)->withHeaders($headers)->post($this::BASE_URL, $body);
    }
}

