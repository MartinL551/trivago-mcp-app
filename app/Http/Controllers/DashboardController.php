<?php

namespace App\Http\Controllers;

use App\Models\Accommodation;
use App\Models\SearchRequest;
use App\Services\OpenAIService;
use App\Services\TrivagoMcpService;
use Inertia\Inertia;

class DashboardController extends Controller
{   

    public function index(SearchRequest $searchRequest)
    {
        $testRequest = $searchRequest::where('id', 1)->get()->first();

        return Inertia::render('Dashboard', [
            'searchRequest' => $testRequest,
        ]);
    }
    
}
