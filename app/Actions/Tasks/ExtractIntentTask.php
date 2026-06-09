<?php

namespace app\Actions\Tasks;

use App\Data\LlmData;
use App\Enums\SearchRequestStatus;
use App\Models\SearchRequest;
use App\Services\OpenAIService;

class ExtractIntentTask
{
    public function __construct(
        private OpenAIService $openAiService,
    ) {}

    public function handle(SearchRequest $searchRequest): LlmData
    {
        $prompt = $searchRequest->prompt;

        $intent = $this->openAiService->extractSearchIntent($prompt);

        if ($intent->status === 'success') {
            $searchRequest->main_signal = $intent->mainSignal;
            $searchRequest->secondary_signal = $intent->secondarySignal;
            $searchRequest->setStatus(SearchRequestStatus::Interpreting);
        }

        return $intent;
    }
}
