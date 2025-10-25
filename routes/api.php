<?php

use App\Http\Controllers\TrackingController;
use App\Http\Middleware\ValidateServerToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Tracking API routes
Route::prefix('track')->group(function () {
    // Server-side search initialization (requires authentication)
    Route::post('search/init', [TrackingController::class, 'initSearch'])
        ->middleware(ValidateServerToken::class);

    // Client-side click tracking (public endpoint)
    Route::post('click', [TrackingController::class, 'trackClick']);
});
