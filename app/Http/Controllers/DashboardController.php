<?php

namespace App\Http\Controllers;

use App\Services\OpenAIService;
use App\Services\TrivagoMcpService;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(OpenAIService $aiService, TrivagoMcpService $trivagoMcpService)
    {
        $llmData = $aiService->extractSearchIntent('Romantic adventures in europe with young kids. I want a good restuarnt as part of the hotel with an included breakfast. I am going on holiday next august');


        $mcpData = $trivagoMcpService->getAccomindationsSearch($llmData);

        dd($mcpData);

        return Inertia::render('Dashboard', [
            'res' => $response,
        ]);
    }
}
