<?php

use App\Http\Controllers\PlaceController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Main');
});

Route::get('/search-autoComplete', [PlaceController::class, 'searchAutoCompleteAPI']);
Route::get('/search-placeDetails', [PlaceController::class, 'searchPlaceDetailsAPI']);
Route::post('/search-placeDirection', [PlaceController::class, 'searchPlaceDirectionAPI']);

require __DIR__.'/auth.php';
