<?php

/** @routePrefix("api.") */

use App\Http\Controllers\Api\MovieController;
use App\Http\Controllers\Api\PersonController;
use App\Http\Controllers\Api\TvShowController;
use Illuminate\Support\Facades\Route;

Route::prefix('movie')->name('movie.')->group(static function (): void {
    Route::get('/', [MovieController::class, 'index'])->name('index');
    Route::get('/autocomplete', [MovieController::class, 'autocomplete'])->name('autocomplete');
    Route::post('/assign', [MovieController::class, 'assign'])->name('assign');
    Route::patch('/{movie}/watch', [MovieController::class, 'watch'])->name('watch');
});

Route::prefix('tv-show')->name('tvshow.')->group(static function (): void {
    Route::get('/', [TvShowController::class, 'index'])->name('index');
    Route::get('/autocomplete', [TvShowController::class, 'autocomplete'])->name('autocomplete');
});

Route::prefix('person')->name('person.')->group(static function (): void {
    Route::get('/', [PersonController::class, 'index'])->name('index');
    Route::get('/autocomplete', [PersonController::class, 'autocomplete'])->name('autocomplete');
});
