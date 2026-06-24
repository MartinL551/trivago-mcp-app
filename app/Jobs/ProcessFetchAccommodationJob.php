<?php

namespace App\Jobs;

use App\Actions\Tasks\FetchAccommodationTask;
use App\Data\SearchIntentData;
use App\Enums\SearchRequestStatus;
use App\Jobs\Concerns\FailSearchRequest;
use App\Models\SearchRequest;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use RuntimeException;
use Throwable;

class ProcessFetchAccommodationJob implements ShouldQueue
{
    use Queueable;
    use FailSearchRequest;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private SearchRequest $searchRequest,
        private SearchIntentData $intent,
        private bool $chain = false,
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $this->searchRequest->status = SearchRequestStatus::FetchingAccommodations;
            $this->searchRequest->save();

            $accommodations = app(FetchAccommodationTask::class)->handle($this->searchRequest, $this->intent);

            if (! $accommodations || count($accommodations) <= 0) {
                throw new RuntimeException('No accommodations returned for search request.');
            }

            if ($this->chain) {
                ProcessScoreAccommodationsJob::dispatch($this->searchRequest, $accommodations);
            }
        } catch (Throwable $exception) {
            $this->fail($exception);
        }
    }

    public function failed(Throwable $exception): void
    {
        $this->failSearchRequest($this->searchRequest, $exception, [
            'stage' => SearchRequestStatus::FetchingAccommodations->value,
        ]);
    }
}
