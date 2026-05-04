<?php

namespace app\Actions\Tasks;

use App\Data\LlmData;
use App\Models\SearchRequest;
use App\Services\OpenAIService;
use App\Enums\SearchRequestStatus;
use App\Models\Suggestion;
use App\Services\TrivagoMcpService;

class FetchSuggestionsTask
{
    public function __construct(
        private TrivagoMcpService $mcpSerivce,
        private Suggestion $suggestion,
    ) {}


    public function handle(LlmData $intent, SearchRequest $searchRequest)
    {
       $suggestions = $this->mcpSerivce->getSuggestions($intent);

       dd($suggestions);


    }
}