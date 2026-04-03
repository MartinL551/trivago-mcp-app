<?php

namespace App\Http\Controllers;

use App\Services\OpenAIService;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(OpenAIService $AiService)
    {
        $response = $AiService->extractSearchIntent('Romantic adventures in europe');

        dd($response);

        return Inertia::render('Dashboard', [
            'res' => $response,
        ]);
    }
}
