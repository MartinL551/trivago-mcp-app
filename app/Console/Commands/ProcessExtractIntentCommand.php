<?php

namespace App\Console\Commands;

use App\Actions\Tasks\ExtractIntentTask;
use App\Enums\SearchRequestStatus;
use App\Models\SearchRequest;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Throwable;

#[Signature('search:process {prompt} {--user=1}')]
#[Description('Send a prompt to the LLM')]
class ProcessExtractIntentCommand extends Command
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

            $this->info('Intent from LLM:');
            $this->line(json_encode($intent, JSON_PRETTY_PRINT));

            return self::SUCCESS;
        } catch (Throwable $exception) {
            $this->error('Failed to extract intent.');
            $this->line($exception::class.': '.$exception->getMessage());

            return self::FAILURE;
        }
    }
}
