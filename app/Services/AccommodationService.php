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
    private const BASE_URL = 'https://mcp.trivago.com/mcp';
    private const CACHE_KEY = 'trivago_mcp_session_id';
    private const SUGGEST = 'trivago-search-suggestions';
    private const ACCOMMODATION_SEARCH = 'trivago-accommodation-search';
    private const RADIUS_SEARCH = 'trivago-accommodation-radius-search';

    public function __construct(
        private Accommodation $accommodation,
        private OpenAIService $openAiService
    ) {
    }
}

