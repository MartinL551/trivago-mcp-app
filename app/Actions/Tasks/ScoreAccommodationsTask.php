<?php

namespace app\Actions\Tasks;

use App\Models\AccommodationScore;
use App\Models\SearchRequest;
use App\Services\OpenAIService;
use Illuminate\Database\Eloquent\Collection;

class ScoreAccommodationsTask
{
    public function __construct(
        private OpenAIService $openAiService,
    ) {}

    public function handle(SearchRequest $searchRequest, Collection $accommodations): ?Collection
    {
        $scores = $this->openAiService->getScoreForAccommidations($accommodations, $searchRequest);

        $mappedAccoms = [];

        foreach ($accommodations as $accom) {
            $mappedAccoms[$accom['trivago_id']] = $accom['id'];
        }

        $rows = collect($scores)->map(fn ($score) => [
            'trivago_id' => $score->trivagoId ?? '',
            'accommodation_id' => $mappedAccoms[$score->trivagoId],
            'search_request_id' => $searchRequest->id,
            'romance' => $score->romance ?? 0,
            'adventure' => $score->adventure ?? 0,
            'budget' => $score->budget ?? 0,
            'luxury' => $score->luxury ?? 0,
            'business' => $score->business ?? 0,
            'family' => $score->family ?? 0,
            'why' => $score->why ?? '',

        ])->take(5)->all();

        AccommodationScore::upsert(
            $rows,
            ['trivago_id'],
            [
                'accommodation_id',
                'search_request_id',
                'romance',
                'adventure',
                'budget',
                'luxury',
                'business',
                'family',
                'why',
            ]
        );

        $insertedScores = AccommodationScore::whereIn('accommodation_id', $accommodations->pluck('id'))->latest()->limit(5)->get();

        if (count($insertedScores) <= 0) {
            return null;
        }

        return $insertedScores;
    }
}
