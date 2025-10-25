<?php

use App\Http\Controllers\SiteController;
use App\Http\Controllers\TrackingController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return Inertia::render('welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');

    Route::resource('sites', SiteController::class)
        ->except('edit');
});

// Serve tracking script
Route::get('/js/tracker.js', [TrackingController::class, 'serveScript']);

require __DIR__.'/settings.php';
