<?php

namespace App\Console\Commands;

use App\Actions\Tasks\ExtractIntentTask;
use App\Actions\Tasks\FetchAccommodationTask;
use App\Actions\Tasks\FetchSuggestionsTask;
use App\Actions\Tasks\ScoreAccommodationsTask;
use App\Enums\SearchRequestStatus;
use App\Models\SearchRequest;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use RuntimeException;
use Throwable;

#[Signature('search:scores {prompt} {--user=1}')]
#[Description('Send a prompt to the LLM and Get Accommodations from MCP and Score them')]
class ProcessFetchScoresCommand extends Command
{
    public function handle(): int
    {
        try {
            $searchRequest = SearchRequest::create([
                'user_id' => (int) $this->option('user'),
                'prompt' => $this->argument('prompt'),
                'status' => SearchRequestStatus::Pending->value,
            ]);

            $this->info("Search request {$searchRequest->id} created.");

            $intent = app(ExtractIntentTask::class)->handle($searchRequest);

            if ($intent->status !== 'success') {
                throw new RuntimeException('Failed to get search intent.');
            }

            $this->info('Intent extracted.');

            $suggestions = app(FetchSuggestionsTask::class)->handle($intent, $searchRequest);

            if (! $suggestions || $suggestions->isEmpty()) {
                throw new RuntimeException('No suggestions returned from MCP.');
            }

            $this->info("Suggestions fetched: {$suggestions->count()}");

            $accommodations = app(FetchAccommodationTask::class)->handle($searchRequest, $suggestions->first(), $intent);

            if (! $accommodations || $accommodations->isEmpty()) {
                throw new RuntimeException('No accommodations returned from MCP.');
            }

            $this->info("Accommodations fetched: {$accommodations->count()}");

            $scores = app(ScoreAccommodationsTask::class)->handle($searchRequest, $accommodations);

            if (! $scores || $scores->isEmpty()) {
                throw new RuntimeException('No scores returned from OpenAI.');
            }

            $this->info("Scores processed: {$scores->count()}");

            return self::SUCCESS;
        } catch (Throwable $exception) {
            $this->error('Failed to fetch scores.');
            $this->line($exception::class.': '.$exception->getMessage());

            return self::FAILURE;
        }
    }
}
