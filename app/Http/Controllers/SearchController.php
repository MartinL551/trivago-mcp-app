<?php

namespace App\Http\Controllers;

use App\Concerns\SearchValidationRules;
use App\Jobs\ProcessExtractIntentJob;
use App\Models\SearchRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class SearchController extends Controller
{
    use SearchValidationRules;

    public function index()
    {
        $user = Auth::getUser();
        $recentSearchRequests = $user->searchRequests()->latest()->take(3)->get();
        $dailySearchCount = $user->searchRequests()
            ->where('created_at', '>=', now()->subDay())
            ->count();
    
        return Inertia::render('Search', [
            'recentSearchRequests' => $recentSearchRequests,
            'dailySearchCount' => $dailySearchCount,
            'dailySearchLimit' => config('app.search_limit_per_day'),
        ]);
    }

    public function show()
    {
        $user = Auth::getUser();
        $searchRequests = $user->searchRequests()->get();

        return Inertia::render('Searches', [
            'searchRequests' => $searchRequests,
        ]);
    }

    public function search(Request $request)
    {
        $validated = $request->validate($this->searchRules());
        $user = Auth::getUser();

        $searchesInLastDay = $user->searchRequests()
            ->where('created_at', '>=', now()->subDay())
            ->count();

        if ($searchesInLastDay >= config('app.search_limit_per_day')) {
            return back()->with('flash', [
                'type' => 'warning',
                'message' => 'You have reached your search limit for today. Please try again later.',
            ]);
        }

        $searchRequest = SearchRequest::create([
            'prompt' => $validated['prompt'],
            'user_id' => $user->id,
            'status' => 'pending',
        ]);

        ProcessExtractIntentJob::dispatch($searchRequest, true);

        return redirect()->route('results.show', [
            'searchRequest' => $searchRequest->id,
        ]);
    }

    public function dailySearchCount()
    {
        $user = Auth::getUser();

        $searchesInLastDay = $user->searchRequests()
            ->where('created_at', '>=', now()->subDay())
            ->count();

        return back()->with('searchCount', [
            'count' => $searchesInLastDay,
        ]);
    }
}
