<?php

/** @routePrefix("auth.") */

use App\Http\Controllers\Auth\SignOutController;
use App\Http\Controllers\Auth\TraktController;
use Illuminate\Support\Facades\Route;

Route::prefix('trakt')->name('trakt.')->middleware('guest')->group(static function (): void {
    Route::get('/', [TraktController::class, 'redirect'])->name('redirect');
    Route::get('/callback', [TraktController::class, 'callback'])->name('callback');
});

Route::post('sign-out', SignOutController::class)->middleware('auth')->name('signout');
