<?php

/** @routePrefix("api.") */

use App\Http\Controllers\Api\MovieController;
use App\Http\Controllers\Api\PersonController;
use Illuminate\Support\Facades\Route;

Route::prefix('movie')->name('movie.')->group(static function (): void {
    Route::get('/', [MovieController::class, 'index'])->name('index');
});

Route::prefix('person')->name('person.')->group(static function (): void {
    Route::get('/', [PersonController::class, 'index'])->name('index');
});
