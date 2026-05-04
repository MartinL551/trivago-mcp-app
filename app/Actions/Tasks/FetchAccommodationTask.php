<?php

namespace app\Actions\Tasks;

use App\Data\LlmData;
use App\Models\SearchRequest;
use App\Services\OpenAIService;
use App\Enums\SearchRequestStatus;
use App\Services\TrivagoMcpService;

class FetchAccommodationTask
{
    public function __construct(
        private TrivagoMcpService $mcpSerivce,
    ) {}


    public function handle(SearchRequest $searchRequest): LlmData
    {
        $prompt = $searchRequest->prompt;

        

        return $intent;
    }
}