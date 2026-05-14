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
            'accommodations' => (
                $searchRequest->status === SearchRequestStatus::Complete->value || $searchRequest->status === SearchRequestStatus::Scoring->value) 
                    ? Inertia::optional(fn () => $searchRequest->accommodations()
                        ->with('scores')
                        ->whereHas('scores', function($query) use ($searchRequest) {
                            $query->where('search_request_id', $searchRequest->id);
                        })
                        ->latest()
                        ->limit(5)->get()) : null,
        ]);
    }
    
}
