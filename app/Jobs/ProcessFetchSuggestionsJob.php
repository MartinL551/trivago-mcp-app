<?php

namespace App\Jobs;

use App\Actions\Tasks\FetchSuggestionsTask;
use App\Data\LlmData;
use App\Enums\SearchRequestStatus;
use App\Jobs\Concerns\FailSearchRequest;
use App\Models\SearchRequest;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use RuntimeException;
use Throwable;

class ProcessFetchSuggestionsJob implements ShouldQueue
{
    use Queueable;
    use FailSearchRequest;

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
        try {
            $this->searchRequest->status = SearchRequestStatus::FetchingSuggestions;
            $this->searchRequest->save();

            $suggestions = app(FetchSuggestionsTask::class)->handle($this->intent, $this->searchRequest);

            if (! $suggestions || count($suggestions) <= 0) {
                throw new RuntimeException('No suggestions returned for search request.');
            }

            if ($this->chain) {
                foreach ($suggestions as $suggestion) {
                    ProcessFetchAccommodationJob::dispatch($this->searchRequest, $suggestion, $this->intent, true);
                }
            }
        } catch (Throwable $exception) {
            $this->fail($exception);
        }
    }

    public function failed(Throwable $exception): void
    {
        $this->failSearchRequest($this->searchRequest, $exception, [
            'stage' => SearchRequestStatus::FetchingSuggestions->value,
        ]);
    }
}
