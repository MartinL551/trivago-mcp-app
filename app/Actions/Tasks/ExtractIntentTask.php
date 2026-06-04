<?php

namespace app\Actions\Tasks;

use App\Data\LlmData;
use App\Models\SearchRequest;
use App\Services\OpenAIService;
use App\Enums\SearchRequestStatus;
use App\Enums\PromptSignals;

class ExtractIntentTask
{
    public function __construct(
        private OpenAiService $openAiService,
    ) {}


    public function handle(SearchRequest $searchRequest): LlmData
    {
        $prompt = $searchRequest->prompt;

        $intent = $this->openAiService->extractSearchIntent($prompt);

        if($intent->status === "success"){
            $searchRequest->main_signal = $intent->mainSignal;
            $searchRequest->secondary_signal = $intent->secondarySignal;
            $searchRequest->setStatus(SearchRequestStatus::Interpreting);
        }
      

        return $intent;
    }
}