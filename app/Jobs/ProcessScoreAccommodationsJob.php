<?php

namespace App\Jobs;

use App\Actions\Tasks\ScoreAccommodationsTask;
use App\Enums\SearchRequestStatus;
use App\Jobs\Concerns\FailSearchRequest;
use App\Models\SearchRequest;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Queue\Queueable;
use RuntimeException;
use Throwable;

class ProcessScoreAccommodationsJob implements ShouldQueue
{
    use Queueable;
    use FailSearchRequest;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private SearchRequest $searchRequest,
        private Collection $accommodations,
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $this->searchRequest->status = SearchRequestStatus::Scoring;
            $this->searchRequest->save();

            $scores = app(ScoreAccommodationsTask::class)->handle($this->searchRequest, $this->accommodations);

            if (! $scores || count($scores) <= 0) {
                throw new RuntimeException('No scores returned for search request.');
            }

            $this->searchRequest->status = SearchRequestStatus::Complete;
            $this->searchRequest->save();
        } catch (Throwable $exception) {
            $this->fail($exception);
        }
    }

    public function failed(Throwable $exception): void
    {
        $this->failSearchRequest($this->searchRequest, $exception, [
            'stage' => SearchRequestStatus::Scoring->value,
        ]);
    }
}
