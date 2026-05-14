<?php

namespace App\Http\Controllers;

use App\Enums\SearchRequestStatus;
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
            'accommodations' => ($searchRequest->status === SearchRequestStatus::Complete->value || $searchRequest->status === SearchRequestStatus::Scoring->value) 
                ? Inertia::optional(fn () => $searchRequest->accommodations()->with('score')->latest()->limit(5)->get()) : null,
        ]);
    }
    
}
