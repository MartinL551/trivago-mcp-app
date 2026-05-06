<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use App\Models\SearchRequest;
use App\Actions\Tasks\ExtractIntentTask;
use App\Jobs\ProcessExtractIntentJob;

#[Signature('search:searchRequestChain {prompt}')]
#[Description('Send Request to job queue')]
class ProcessSearchRequestChainCommand extends Command
{
    public function handle(): int
    {
        $prompt = $this->argument('prompt');

        $searchRequest = SearchRequest::create([
            'prompt' => $prompt,
            'status' => 'pending',
        ]);

        ProcessExtractIntentJob::dispatch($searchRequest, true);

        return self::SUCCESS;
    }
}
