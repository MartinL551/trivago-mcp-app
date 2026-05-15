<?php

namespace App\Http\Controllers;

use App\Enums\SearchRequestStatus;
use App\Models\SearchRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ResultsController extends Controller
{   

    public function show(SearchRequest $searchRequest)
    {
       
        return Inertia::render('Results', [
            'initialSearchRequest' => fn () => $searchRequest->only([
                'id',
                'status',
                'prompt',
            ]),
            'initalAccommodations' =>($accoms = $searchRequest->accommodationsForStatus()) ? Inertia::optional(fn () => $accoms->get()) : null,
        ]);
    }

    public function poll(Request $request, SearchRequest $searchRequest)
    {
        $knownIds = $request->input('know_ids');
        $requestedIds = $request->input('requested_ids');

        $accommodationsQuery = $searchRequest->accommodationsForStatus();

        $accommodationsQuery = match($searchRequest->status) {
            SearchRequestStatus::Scoring->value => $accommodationsQuery->whereIn('id', $requestedIds ?? []),
            SearchRequestStatus::Complete->value => $accommodationsQuery,
            default => $accommodationsQuery,
        };

        $accommodations = $accommodationsQuery ? $accommodationsQuery->get() : null ;

        return response()->json([
            'status' => $searchRequest->status,
            'prompt' => $searchRequest->prompt,
            'accommodations' => $accommodations, 
        ]);
    }
}
