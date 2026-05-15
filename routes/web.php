<?php

use App\Http\Controllers\ResultsController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;

Route::get('/results/{searchRequest}', [ResultsController::class, 'show'])->name('results');
Route::get('/search', [SearchController::class, 'index'])->name('Search');



Route::post('/search', [SearchController::class, 'search'])->name('search');
Route::post('/results/{searchRequest}/poll', [ResultsController::class, 'poll'])->name('results.poll');

require __DIR__.'/settings.php';
