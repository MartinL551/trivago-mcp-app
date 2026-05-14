<?php

namespace App\Http\Controllers;

use App\Models\SearchRequest;
use Inertia\Inertia;

class DashboardController extends Controller
{   

    public function index(SearchRequest $searchRequest)
    {
        $testRequest = $searchRequest::with('accommodations')->find(1);


        return Inertia::render('Dashboard', [
            'searchRequest' => $testRequest,
        ]);
    }
    
}
