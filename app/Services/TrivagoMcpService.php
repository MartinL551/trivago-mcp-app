<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class TrivagoMcpService
{
    private const BASE_URL = 'https://mcp.trivago.com/mcp';
    private const CACHE_KEY = 'trivago_mcp_session_id';

    public function __construct() {
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

        $sessionId = $response->header('Mcp-Session-Id');

        if(! $sessionId){
            throw new \RuntimeException('No MCP Session ID');
        }

        Cache::put($this::CACHE_KEY);

        return $sessionId;
    }
    

    public function getResultsFromMcp(array $llmIntent): array {
        $sessionId = $this->getSessionId();

        $headers = [
            'Accept' => 'application/json, text/event-stream',
            'Content-Type' => 'application/json',
            'Mcp-Session-Id' => $sessionId,
        ];

        $jsonBody = [
    
        ];

    }

    private function getMcpArgsFromIntent(array $llmIntent): array{
        foreach($llmIntent as $key => $intent) {
            
        }
    }

    private function sendJson(array $headers, array $body): \Illuminate\Http\Client\Response | \GuzzleHttp\Promise\PromiseInterface{
        return Http::withHeaders($headers)->post($this::BASE_URL, $body);
    }
}

