<?php

use App\Http\Controllers\ResultsController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;

Route::get('/results/{searchRequest}', [ResultsController::class, 'index'])->name('results');
Route::get('/', [SearchController::class, 'index'])->name('Search');
Route::post('/search', [SearchController::class, 'search'])->name('search');

require __DIR__.'/settings.php';
