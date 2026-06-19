<?php

namespace App\Console\Commands;

use App\Actions\Tasks\ExtractIntentTask;
use App\Actions\Tasks\FetchSuggestionsTask;
use App\Enums\SearchRequestStatus;
use App\Models\SearchRequest;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use RuntimeException;
use Throwable;

#[Signature('search:suggestions {prompt} {--user=1}')]
#[Description('Send a prompt to the LLM and Get Suggestions from MCP')]
class ProcessFetchSuggestionsCommand extends Command
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

            return self::SUCCESS;
        } catch (Throwable $exception) {
            $this->error('Failed to fetch suggestions.');
            $this->line($exception::class.': '.$exception->getMessage());

            return self::FAILURE;
        }
    }
}
