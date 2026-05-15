<?php

namespace App\Http\Controllers;

use App\Enums\SearchRequestStatus;
use App\Models\SearchRequest;
use Inertia\Inertia;

class ResultsController extends Controller
{   

    public function show(SearchRequest $searchRequest)
    {
       
        return Inertia::render('Results', [
            'searchRequest' => fn () => $searchRequest->only([
                'id',
                'status',
                'prompt',
            ]),
            'accommodations' =>($accoms = $searchRequest->accommodationsForStatus()) ? Inertia::optional(fn () => $accoms->get()) : null,
        ]);
    }
}
