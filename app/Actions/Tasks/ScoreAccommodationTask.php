<?php

namespace app\Actions\Tasks;

use App\Data\LlmData;
use App\Models\SearchRequest;
use App\Services\OpenAIService;
use App\Enums\SearchRequestStatus;
use App\Models\Accommodation;
use App\Models\AccommodationScore;
use Illuminate\Database\Eloquent\Collection;

class ScoreAccommodationTask
{
    public function __construct(
        private OpenAiService $openAiService,
    ) {}


    public function handle(SearchRequest $searchRequest, Collection $accommodations): ?Collection
    {
        $scores = $this->openAiService->getScoreForAccommidations($accommodations);
    }
}