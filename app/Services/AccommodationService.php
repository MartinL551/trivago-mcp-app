<?php

namespace App\Services;

use App\Data\LlmData;
use App\Data\McpSearchMapper;
use App\Models\Accommodation;
use App\Models\AccommodationScore;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class AccommodationService
{
    public function __construct(
        private Accommodation $accommodation,
        private AccommodationScore $score,
        private OpenAIService $openAiService
    ) {
    }
}

