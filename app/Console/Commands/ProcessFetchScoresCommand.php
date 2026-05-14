<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use App\Models\SearchRequest;
use App\Actions\Tasks\ExtractIntentTask;
use App\Actions\Tasks\FetchSuggestionsTask;
use App\Actions\Tasks\FetchAccommodationTask;
use App\Actions\Tasks\ScoreAccommodationsTask;

#[Signature('search:scores {prompt}')]
#[Description('Send a prompt to the LLM and Get Accommodations from MCP and Score them')]
class ProcessFetchScoresCommand extends Command
{
    public function handle(): int
    {
        $prompt = $this->argument('prompt');

        $searchRequest = SearchRequest::create([
            'prompt' => $prompt,
            'status' => 'pending',
        ]);

        $this->info("Search for Request {$searchRequest->id}");

        $intent = app(ExtractIntentTask::class)->handle($searchRequest);
        $this->info("Proccesed intent");
        if($intent->status === 'Failed'){
            $this->error('Failed to Get Search intent');
            return self::FAILURE;
        }

        $suggestions = app(FetchSuggestionsTask::class)->handle($intent, $searchRequest);
        $this->info("Proccesed Sgguestions");
        $firstSuggestion = $suggestions->first();

        $accomidations = app(FetchAccommodationTask::class)->handle($searchRequest, $firstSuggestion, $intent);
        $this->info("Proccesed accoms");
        $scores = app(ScoreAccommodationsTask::class)->handle($searchRequest, $accomidations);
        $this->info("Proccesed Scores");


        return self::SUCCESS;
    }
}
