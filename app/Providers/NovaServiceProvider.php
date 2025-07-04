<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Laravel\Nova\Nova;
use Laravel\Nova\NovaApplicationServiceProvider;

class NovaServiceProvider extends NovaApplicationServiceProvider
{
    protected function routes(): void
    {
        Nova::routes()
            ->withAuthenticationRoutes()
            ->withPasswordResetRoutes()
            ->register();
    }

    protected function gate(): void
    {
        Gate::define('viewNova', function (User $user): bool {
            return in_array($user->slug, [
                'gummibeer',
            ], true);
        });
    }

    protected function dashboards(): array
    {
        return [
            new \App\Nova\Dashboards\Main,
        ];
    }
}
