<?php

namespace App\Http\Controllers;

use App\Enums\SearchRequestStatus;
use App\Models\SearchRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class ResultsController extends Controller
{   
    public function index()
    {
        $user = Auth::getUser();
       
        if($lastSearchReqest = $user->searchRequests()->get()->last()) {
            return redirect()->route('results.show',[
                'searchRequest' => $lastSearchReqest->id,
            ]);
        } else {
            return redirect()->route('search.index');
        }

    }

    public function show(SearchRequest $searchRequest)
    {
        abort_unless($searchRequest->user_id === Auth::id(), 403);

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
            SearchRequestStatus::Complete->value => $accommodationsQuery->whereNotIn('id', $knownIds ?? []),
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
