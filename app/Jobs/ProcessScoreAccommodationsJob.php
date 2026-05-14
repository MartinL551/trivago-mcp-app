<?php

namespace App\Jobs;

use App\Services\OpenAIService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Actions\Tasks\FetchAccommodationTask;
use App\Actions\Tasks\ScoreAccommodationsTask;
use App\Data\LlmData;
use App\Enums\SearchRequestStatus;
use App\Models\SearchRequest;
use App\Models\Suggestion;
use Illuminate\Database\Eloquent\Collection;

class ProcessScoreAccommodationsJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private SearchRequest $searchRequest, 
        private Collection $accommodations,
    )
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->searchRequest->status = SearchRequestStatus::Scoring;
        $this->searchRequest->save();

        $scores = app(ScoreAccommodationsTask::class)->handle($this->searchRequest, $this->accommodations);

        if($scores && count($scores) > 0) {
            $this->searchRequest->status = SearchRequestStatus::Complete;
            $this->searchRequest->save();
        }
    }
}
