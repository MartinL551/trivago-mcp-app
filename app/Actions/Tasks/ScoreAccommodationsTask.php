<?php

namespace app\Actions\Tasks;

use App\Data\LlmData;
use App\Models\SearchRequest;
use App\Services\OpenAIService;
use App\Enums\SearchRequestStatus;
use App\Models\Accommodation;
use App\Models\AccommodationScore;
use Illuminate\Database\Eloquent\Collection;

class ScoreAccommodationsTask
{
    public function __construct(
        private OpenAiService $openAiService,
    ) {}


    public function handle(SearchRequest $searchRequest, Collection $accommodations): ?Collection
    {
        $scores = $this->openAiService->getScoreForAccommidations($accommodations);

        $mappedAccoms = [];

        foreach($accommodations as $accom) {
            $mappedAccoms[$accom['trivago_id']] = $accom['id'];
        }

        $rows = collect($scores)->map(fn ($score)  => [
            'trivago_id' => $score->trivagoId ?? '',
            'accommodation_id' => $mappedAccoms[$score->trivagoId],
            'romance' => $score->romance ?? 0,
            'adventure' => $score->adventure ?? 0,
            'budget' => $score->budget ?? 0,
            
        ])->take(5)->all();

        AccommodationScore::upsert(
            $rows,
            ['trivago_id'],
            [
                'trivago_id',
                'accommodation_id',
                'romance',
                'adventure',
                'budget'
            ]
        );

        $insertedScores = AccommodationScore::whereIn('trivago_id', collect($rows)->pluck('trivago_id'))->get();

        return $insertedScores;
    }
}