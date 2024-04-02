<?php

/** @routePrefix("app.") */

use App\Http\Controllers\MovieController;
use App\Http\Controllers\PersonController;
use Illuminate\Support\Facades\Route;

Route::prefix('movie')->name('movie.')->group(static function (): void {
    Route::get('/', [MovieController::class, 'index'])->name('index');
    Route::get('/{movie}', [MovieController::class, 'show'])->name('show')->whereNumber('movie');
    Route::get('/popular', [MovieController::class, 'popular'])->name('popular');
    Route::get('/recommend', [MovieController::class, 'recommend'])->name('recommend');
    Route::get('/trending', [MovieController::class, 'trending'])->name('trending');
    Route::get('/toprated', [MovieController::class, 'toprated'])->name('toprated');
    Route::get('/upcoming', [MovieController::class, 'upcoming'])->name('upcoming');
    Route::get('/collectable', [MovieController::class, 'collectable'])->name('collectable');
});

Route::prefix('person')->name('person.')->group(static function (): void {
    Route::get('/{person}', [PersonController::class, 'show'])->name('show')->whereNumber('person');
});
