<?php

namespace App\Jobs;

use App\Services\OpenAIService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Actions\Tasks\ExtractIntentTask;
use App\Enums\SearchRequestStatus;
use App\Models\SearchRequest;

class ProcessExtractIntentJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private SearchRequest $searchRequest, 
        private bool $chain = false,
    )
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        $this->searchRequest->status = SearchRequestStatus::Interpreting;
        $this->searchRequest->save();

        $intent = app(ExtractIntentTask::class)->handle($this->searchRequest);

        if($intent->status === 'success' && $this->chain) {
            ProcessFetchSuggestionsJob::dispatch($this->searchRequest, $intent, true);
        }
        
    }
}
