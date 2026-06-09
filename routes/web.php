<?php

use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\ResultsController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingPageController::class, 'index'])->name('landingpage.index');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('auth.google.callback');
Route::get('/auth/google/redirect', [GoogleAuthController::class, 'redirect'])->name('auth.google.redirect');

Route::middleware(['auth'])->group(function () {
    Route::get('/results', [ResultsController::class, 'index'])->name('results.index');
    Route::get('/results/{searchRequest}', [ResultsController::class, 'show'])->name('results.show');
    Route::get('/search', [SearchController::class, 'index'])->name('search.index');
    Route::get('/wishlist', [WishlistController::class, 'show'])->name('wishlist.show');

    Route::post('/search', [SearchController::class, 'search'])->name('search.search');
    Route::post('/results/{searchRequest}/poll', [ResultsController::class, 'poll'])->name('results.poll');
    Route::post('/wishlist/{accommodation}/add', [WishlistController::class, 'add'])->name('wishlist.add');
    Route::post('/wishlist/{accommodation}/remove', [WishlistController::class, 'remove'])->name('wishlist.remove');
});

require __DIR__.'/settings.php';
