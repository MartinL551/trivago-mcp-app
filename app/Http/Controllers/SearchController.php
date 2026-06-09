<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessExtractIntentJob;
use App\Models\SearchRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

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
            'user_id' => Auth::id(),
            'status' => 'pending',
        ]);

        ProcessExtractIntentJob::dispatch($searchRequest, true);

        return redirect()->route('results.show', [
            'searchRequest' => $searchRequest->id,
        ]);
    }
}
