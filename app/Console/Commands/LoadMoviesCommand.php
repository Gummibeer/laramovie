<?php

namespace App\Console\Commands;

use App\Workflows\LoadMovie;
use Illuminate\Support\Facades\Storage;

class LoadMoviesCommand extends Command
{
    protected $signature = 'movie:load';

    public function handle(): int
    {
        $directories = collect(Storage::disk('movies')->directories());

        $bar = $this->startProgressBar($directories->count());

        $directories->each(fn (string $directory) => tap(
            dispatch(fn () => LoadMovie::run('movies', $directory)),
            fn () => $bar->advance()
        ));

        $bar->finish();

        return self::SUCCESS;
    }
}
