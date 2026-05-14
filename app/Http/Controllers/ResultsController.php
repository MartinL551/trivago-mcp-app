<?php

namespace App\Http\Controllers;

use App\Models\SearchRequest;
use Inertia\Inertia;

class ResultsController extends Controller
{   

    public function index(SearchRequest $searchRequest)
    {
        return Inertia::render('Results', [
            'searchRequest' => fn () => $searchRequest->only([
                'id',
                'status',
                'prompt',
            ]),
            'accommodations' => Inertia::optional(
                fn () => $searchRequest->accommodations()->latest()->limit(5)->get()
            )
        ]);
    }
    
}
