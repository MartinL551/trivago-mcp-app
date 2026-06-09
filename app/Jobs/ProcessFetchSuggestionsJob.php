<?php

namespace App\Jobs;

use App\Actions\Tasks\FetchSuggestionsTask;
use App\Data\LlmData;
use App\Enums\SearchRequestStatus;
use App\Models\SearchRequest;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessFetchSuggestionsJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private SearchRequest $searchRequest,
        private LlmData $intent,
        private bool $chain = false,
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->searchRequest->status = SearchRequestStatus::FetchingSuggestions;
        $this->searchRequest->save();

        $suggestions = app(FetchSuggestionsTask::class)->handle($this->intent, $this->searchRequest);

        if ($suggestions && count($suggestions) > 0 && $this->chain) {
            foreach ($suggestions as $suggestion) {
                ProcessFetchAccommodationJob::dispatch($this->searchRequest, $suggestion, $this->intent, true);
            }
        } else {
            $this->searchRequest->status = SearchRequestStatus::Failed;
            $this->searchRequest->save();
        }

    }
}
