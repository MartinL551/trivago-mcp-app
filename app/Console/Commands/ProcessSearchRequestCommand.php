<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use App\Models\SearchRequest;
use App\Actions\Tasks\ProcessSearchRequestTask;

#[Signature('search:process {prompt}')]
#[Description('Send a prompt to the LLM')]
class ProcessSearchRequestCommand extends Command
{
    public function handle(): int
    {
          $prompt = $this->argument('prompt');

        $searchRequest = SearchRequest::create([
            'prompt' => $prompt,
            'status' => 'pending',
        ]);

        app(ProcessSearchRequestTask::class)->handle($searchRequest);

        $this->info("Search created: {$searchRequest->id}");

        return self::SUCCESS;
    }
}
