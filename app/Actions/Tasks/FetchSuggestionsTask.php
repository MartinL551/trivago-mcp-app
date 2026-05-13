<?php

namespace app\Actions\Tasks;

use App\Data\LlmData;
use App\Models\SearchRequest;
use App\Services\OpenAIService;
use App\Enums\SearchRequestStatus;
use App\Models\Suggestion;
use App\Services\TrivagoMcpService;
use Illuminate\Database\Eloquent\Collection;

class FetchSuggestionsTask
{
    public function __construct(
        private TrivagoMcpService $mcpSerivce,
    ) {}


    public function handle(LlmData $intent, SearchRequest $searchRequest): ?Collection
    {
        $suggestions = $this->mcpSerivce->getSuggestions($intent);

        $rows = collect($suggestions)->map(fn ($suggestion)  => [
            'trivago_id' => $suggestion['id'],
            'trivago_ns' => $suggestion['ns'],
            'id_ns' => (string) $suggestion['id'] . "_" . (string) $suggestion['ns'],
            'location' => $suggestion['location'],
            'location_label' => $suggestion['location_label'],
            'location_type' => $suggestion['location_type'],
        ])->take(20)->all();

       Suggestion::upsert(
            $rows,
            ['id_ns'],
            [
                'trivago_id', 
                'trivago_ns', 
                'location',
                'location_label', 
                'location_type', 
            ]
        );

        $insertedSuggestions = Suggestion::whereIn('id_ns', collect($rows)->pluck('id_ns'))->get();

        if(count($insertedSuggestions) > 0){
            $searchRequest->suggestions()->syncWithoutDetaching($insertedSuggestions);
        } else {
            return null;
        }
   
        return $insertedSuggestions;
    }
}