<?php

namespace App\Console;

use App\Console\Commands\LoadMoviesCommand;
use App\Console\Commands\LoadTvShowsCommand;
use App\Console\Commands\RecommendMoviesCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command(LoadMoviesCommand::class)->hourly()
            ->runInBackground()
            ->withoutOverlapping();

        /*
        $schedule->command(LoadTvShowsCommand::class)->hourly()
            ->runInBackground()
            ->withoutOverlapping();
        */

        $schedule->command(RecommendMoviesCommand::class)->daily();
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');
    }
}
