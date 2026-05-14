<?php

namespace App\Http\Controllers;

use App\Models\SearchRequest;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Jobs\ProcessExtractIntentJob;

class SearchController extends Controller
{
    public function index()
    {
        return Inertia::render('Search');
    }

    public function search(Request $request)
    {
        $requestBody = $request->toArray();

        $searchRequest = SearchRequest::create([
            'prompt' => $requestBody['prompt'],
            'status' => 'pending',
        ]);

        ProcessExtractIntentJob::dispatch($searchRequest, true);

        return redirect()->route('results',[
            'searchRequest' => $searchRequest->id,
        ]);
    }
}
