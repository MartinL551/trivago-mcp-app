<?php

namespace App\Console\Commands;

use App\Jobs\ProcessExtractIntentJob;
use App\Enums\SearchRequestStatus;
use App\Models\SearchRequest;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Throwable;

#[Signature('search:searchRequestChain {prompt} {--user=1}')]
#[Description('Send Request to job queue')]
class ProcessSearchRequestChainCommand extends Command
{
    public function handle(): int
    {
        try {
            $searchRequest = SearchRequest::create([
                'user_id' => (int) $this->option('user'),
                'prompt' => $this->argument('prompt'),
                'status' => SearchRequestStatus::Pending->value,
            ]);

            ProcessExtractIntentJob::dispatch($searchRequest, true);

            $this->info("Search request {$searchRequest->id} queued.");

            return self::SUCCESS;
        } catch (Throwable $exception) {
            $this->error('Failed to queue search request.');
            $this->line($exception::class.': '.$exception->getMessage());

            return self::FAILURE;
        }
    }
}
