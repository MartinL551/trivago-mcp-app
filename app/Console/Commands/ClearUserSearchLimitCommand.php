<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('search:clear-limit {userId} {--hours=25}')]
#[Description('Move a user\'s recent search requests outside the rolling search limit window')]
class ClearUserSearchLimitCommand extends Command
{
    public function handle(): int
    {
        $user = User::find((int) $this->argument('userId'));

        if (! $user) {
            $this->error('User not found.');

            return self::FAILURE;
        }

        $hours = max((int) $this->option('hours'), 25);
        $movedTo = now()->subHours($hours);

        $updated = $user->searchRequests()
            ->where('created_at', '>=', now()->subDay())
            ->update([
                'created_at' => $movedTo,
                'updated_at' => $movedTo,
            ]);

        $this->info("Moved {$updated} recent search request(s) outside the rolling search limit window.");
        $this->line("User: {$user->id} ({$user->name})");
        $this->line("New timestamp: {$movedTo->toDateTimeString()}");

        return self::SUCCESS;
    }
}
