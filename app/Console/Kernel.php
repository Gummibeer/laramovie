<?php

namespace App\Console;

use App\Console\Commands\LoadCollectionsCommand;
use App\Console\Commands\LoadMoviesCommand;
use App\Console\Commands\LoadOwnedMoviesCommand;
use App\Console\Commands\LoadPeopleCommand;
use App\Console\Commands\LoadTmdbCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command(LoadOwnedMoviesCommand::class)->hourly()
            ->runInBackground()
            ->withoutOverlapping();

        $schedule->command(LoadTmdbCommand::class)->daily()
            ->runInBackground()
            ->withoutOverlapping();

        $schedule->command(LoadMoviesCommand::class)->daily()
            ->runInBackground()
            ->withoutOverlapping();

        $schedule->command(LoadCollectionsCommand::class)->daily()
            ->runInBackground()
            ->withoutOverlapping();

        $schedule->command(LoadPeopleCommand::class)->daily()
            ->runInBackground()
            ->withoutOverlapping();
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');
    }
}
