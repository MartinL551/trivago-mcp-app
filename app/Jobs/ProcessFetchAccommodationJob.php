<?php

namespace App\Jobs;

use App\Actions\Tasks\FetchAccommodationTask;
use App\Data\LlmData;
use App\Enums\SearchRequestStatus;
use App\Models\SearchRequest;
use App\Models\Suggestion;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessFetchAccommodationJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private SearchRequest $searchRequest,
        private Suggestion $suggestion,
        private LlmData $intent,
        private bool $chain = false,
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        $this->searchRequest->status = SearchRequestStatus::FetchingAccommodations;
        $this->searchRequest->save();

        $accommodations = app(FetchAccommodationTask::class)->handle($this->searchRequest, $this->suggestion, $this->intent);

        if ($accommodations && count($accommodations) > 0 && $this->chain) {
            ProcessScoreAccommodationsJob::dispatch($this->searchRequest, $accommodations);
        } else {
            $this->searchRequest->status = SearchRequestStatus::Failed;
            $this->searchRequest->save();
        }

    }
}
