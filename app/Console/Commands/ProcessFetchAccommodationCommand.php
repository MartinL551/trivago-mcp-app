<?php

namespace App\Console\Commands;

use App\Actions\Tasks\ExtractIntentTask;
use App\Actions\Tasks\FetchAccommodationTask;
use App\Enums\SearchRequestStatus;
use App\Models\SearchRequest;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use RuntimeException;
use Throwable;

#[Signature('search:fetchaccom {prompt} {--user=1}')]
#[Description('Send a prompt to the LLM and Get Accommodations from MCP')]
class ProcessFetchAccommodationCommand extends Command
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

            $accommodations = app(FetchAccommodationTask::class)->handle($searchRequest, $intent);

            if (! $accommodations || $accommodations->isEmpty()) {
                throw new RuntimeException('No accommodations returned from MCP.');
            }

            $this->info("Accommodations fetched: {$accommodations->count()}");

            return self::SUCCESS;
        } catch (Throwable $exception) {
            $this->error('Failed to fetch accommodations.');
            $this->line($exception::class.': '.$exception->getMessage());

            return self::FAILURE;
        }
    }
}
