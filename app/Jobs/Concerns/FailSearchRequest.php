<?php

namespace App\Jobs\Concerns;

use App\Models\SearchRequest;
use App\Enums\SearchRequestStatus;
use Illuminate\Support\Facades\Log;
use Throwable;

trait FailSearchRequest
{
    protected function failSearchRequest(
        SearchRequest $searchRequest,
        Throwable $exception,
        array $context = [],
    ) {
        $searchRequest->setStatus(SearchRequestStatus::Failed);

        Log::error('Search request pipeline failed',
        [
            'search_request_id' => $searchRequest->id,
            'job' => static::class,
            ...$context,
            'exception' => $exception::class,
            'message' => $exception->getMessage(),
        ]);
    }
}

