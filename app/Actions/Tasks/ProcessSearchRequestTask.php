<?php

namespace app\Actions\Tasks;
use App\Models\SearchRequest;

class ProcessSearchRequestTask
{
    public function handle(SearchRequest $searchRequest): void
    {
        $prompt = $searchRequest->prompt;
    }
}