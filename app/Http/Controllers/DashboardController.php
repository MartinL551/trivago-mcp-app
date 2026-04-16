<?php

namespace App\Http\Controllers;

use App\Services\OpenAIService;
use App\Services\TrivagoMcpService;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(OpenAIService $aiService, TrivagoMcpService $trivagoMcpService)
    {
        $llmData = $aiService->extractSearchIntent('Area with lots of hikes in Germany. Only two adults are going. I am on budget of £2000. I want somewhere with mountains');


        $mcpData = $trivagoMcpService->getAccomindationsSearch($llmData);
        // $mcpData = $trivagoMcpService->getSuggestions($llmData);

        dd($mcpData);

        return Inertia::render('Dashboard', [
            'res' => $response,
        ]);
    }
}
