<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use App\Models\SearchRequest;

#[Signature('app:process-search-request-command')]
#[Description('Command description')]
class ProcessSearchRequestCommand extends Command
{
    public function handle(): int
    {
        $searchRequest = SearchRequest::create([
            'prompt' => 'romantic hotel in London',
            'status' => 'pending',
        ]);

        app(ProcessSearchRequestTask::class)->handle($searchRequest);

        return self::SUCCESS;
    }
}
