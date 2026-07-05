<?php

namespace App\Console\Commands;

use App\Actions\Tasks\PruneAccommodationsTask;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Throwable;

#[Signature('search:prune')]
#[Description('Prune old search requests that do not have wishlisted accommodations')]
class PruneSearchRequestsCommand extends Command
{
    public function handle(PruneAccommodationsTask $task): int
    {
        try {
            $deleted = $task->handle();

            $this->info("Pruned {$deleted} old search request(s).");

            return self::SUCCESS;
        } catch (Throwable $exception) {
            $this->error('Failed to prune search requests.');
            $this->line($exception::class.': '.$exception->getMessage());

            return self::FAILURE;
        }
    }
}
