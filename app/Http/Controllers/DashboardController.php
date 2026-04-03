<?php

namespace App\Http\Controllers;

use App\Services\OpenAIService;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(OpenAIService $AiService)
    {
        $response = $AiService->sendMessage('Romantic holidays in ');

        return Inertia::render('Dashboard', [
            'msg' => $response,
        ]);
    }
}
