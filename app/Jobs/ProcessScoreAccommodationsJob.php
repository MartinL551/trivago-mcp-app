<?php

namespace App\Jobs;

use App\Actions\Tasks\ScoreAccommodationsTask;
use App\Enums\SearchRequestStatus;
use App\Models\SearchRequest;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Queue\Queueable;

class ProcessScoreAccommodationsJob implements ShouldQueue
{
    use Queueable;

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
        $this->searchRequest->status = SearchRequestStatus::Scoring;
        $this->searchRequest->save();

        $scores = app(ScoreAccommodationsTask::class)->handle($this->searchRequest, $this->accommodations);

        if ($scores) {
            $this->searchRequest->status = SearchRequestStatus::Complete;
            $this->searchRequest->save();
        } else {
            $this->searchRequest->status = SearchRequestStatus::Failed;
            $this->searchRequest->save();
        }
    }
}
