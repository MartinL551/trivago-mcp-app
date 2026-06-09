<?php

namespace App\Console\Commands;

use App\Jobs\ProcessExtractIntentJob;
use App\Models\SearchRequest;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

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
