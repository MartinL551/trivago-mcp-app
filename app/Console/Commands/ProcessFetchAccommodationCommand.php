<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use App\Models\SearchRequest;
use App\Actions\Tasks\ExtractIntentTask;
use App\Actions\Tasks\FetchSuggestionsTask;
use App\Actions\Tasks\FetchAccommodationTask;

#[Signature('search:fetchaccom {prompt}')]
#[Description('Send a prompt to the LLM and Get Accommodations from MCP')]
class ProcessFetchAccommodationCommand extends Command
{
    public function handle(): int
    {
        $prompt = $this->argument('prompt');

        $searchRequest = SearchRequest::create([
            'user_id' => 1,
            'prompt' => $prompt,
            'status' => 'pending',
        ]);

        $this->info("Search for Request {$searchRequest->id}");

        $intent = app(ExtractIntentTask::class)->handle($searchRequest);

        if($intent->status === 'Failed'){
            $this->error('Failed to Get Search intent');
            return self::FAILURE;
        }

        $suggestions = app(FetchSuggestionsTask::class)->handle($intent, $searchRequest);

        $firstSuggestion = $suggestions->first();

        $accomidations = app(FetchAccommodationTask::class)->handle($searchRequest, $firstSuggestion, $intent);

        return self::SUCCESS;
    }
}
