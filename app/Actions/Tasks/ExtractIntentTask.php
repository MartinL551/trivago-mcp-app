<?php

namespace app\Actions\Tasks;

use App\Data\LlmData;
use App\Enums\SearchRequestStatus;
use App\Models\SearchRequest;
use App\Services\NominatimGeocodingService;
use App\Services\OpenAIService;

class ExtractIntentTask
{
    public function __construct(
        private OpenAIService $openAiService,
        private NominatimGeocodingService $geoCodeService,
    ) {}

    public function handle(SearchRequest $searchRequest): LlmData
    {
        $prompt = $searchRequest->prompt;

        $intent = $this->openAiService->extractSearchIntent($prompt);

        if ($intent->status === 'success') {
          
            $searchRequest->main_signal = $intent->mainSignal;
            $searchRequest->secondary_signal = $intent->secondarySignal;
            $searchRequest->city = $intent->city;
            $searchRequest->country = $intent->country;
            $searchRequest->landmark = $intent->landmark;
            
            $searchCoords = $this->geoCodeService->geocode($intent->city, $intent->country ?? '', $intent->landmark ?? '');
            $searchRequest->latitude = $searchCoords->latitude;
            $searchRequest->longitude = $searchCoords->longitude;

            $user = $searchRequest->user;
            $searchRequest->currency = $user->preferred_currency ?? null;

            $searchRequest->setStatus(SearchRequestStatus::Interpreting);
        }

        return $intent;
    }
}
