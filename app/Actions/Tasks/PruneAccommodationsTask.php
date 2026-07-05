<?php

namespace App\Actions\Tasks;

use App\Models\SearchRequest;

class PruneAccommodationsTask
{
    public function handle(): int
    {
        return (new SearchRequest())->pruneAll();
    }
}
