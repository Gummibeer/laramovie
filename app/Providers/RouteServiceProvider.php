<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public const HOME = '/';

    public function boot(): void
    {
        $this->routes(function () {
            Route::prefix('auth')
                ->name('auth.')
                ->middleware(['web'])
                ->group(base_path('routes/auth.php'));

            Route::prefix('app')
                ->name('app.')
                ->middleware(['web', 'auth'])
                ->group(base_path('routes/app.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }
}
