<?php

use Carbon\CarbonInterval;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::get('/', function () {
    return 'home';
})->name('home');

Route::get('stream/{disk}/{filepath}', function (Illuminate\Http\Request $request) {
    $filepath = base64_decode($request->route('filepath'));

    $expiresIn = CarbonInterval::day();

    $headers = [
        'Cache-Control' => sprintf(
            'max-age=%d, private',
            $expiresIn->totalSeconds
        ),
        'Expires' => now()->add($expiresIn)->toRfc7231String(),
        'Pragma' => 'private',
    ];

    return response()->storageStream(
        Storage::disk($request->route('disk')),
        $filepath,
        $headers
    );
})->middleware('signed')->name('stream');
