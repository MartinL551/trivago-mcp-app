<?php

namespace app\Actions\Tasks;

use App\Data\LlmData;
use App\Models\SearchRequest;
use App\Services\OpenAIService;

class ProcessSearchRequestTask
{
    public function __construct(
        private OpenAiService $openAiService,
    ) {}


    public function handle(SearchRequest $searchRequest): LlmData
    {
        $prompt = $searchRequest->prompt;

        $intent = $this->openAiService->extractSearchIntent($prompt);

        return $intent;
    }
}