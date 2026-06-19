<?php

namespace App\Jobs;

use App\Actions\Tasks\ExtractIntentTask;
use App\Enums\SearchRequestStatus;
use App\Jobs\Concerns\FailSearchRequest;
use App\Models\SearchRequest;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Throwable;

class ProcessExtractIntentJob implements ShouldQueue
{
    use Queueable;
    use FailSearchRequest;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private SearchRequest $searchRequest,
        private bool $chain = false,
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $this->searchRequest->status = SearchRequestStatus::Interpreting;
            $this->searchRequest->save();

            $intent = app(ExtractIntentTask::class)->handle($this->searchRequest);

            if ($this->chain) {
                ProcessFetchAccommodationJob::dispatch($this->searchRequest, $intent, true);
            } 

        } catch (Throwable $exception)  {
            $this->fail($exception);
        }
    }

    public function failed(Throwable $exception): void
    {
        $this->failSearchRequest($this->searchRequest, $exception, [
            'stage' => SearchRequestStatus::Interpreting->value,
        ]);
    }
}
