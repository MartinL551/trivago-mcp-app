<?php

namespace App\Http\Controllers;

use App\Services\OpenAIService;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(OpenAIService $AiService)
    {
        $response = $AiService->extractSearchIntent('Romantic adventures in europe with young kids. I want a good restuarnt as part of the hotel with an included breakfast');

        dd($response);

        return Inertia::render('Dashboard', [
            'res' => $response,
        ]);
    }
}
