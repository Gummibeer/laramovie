<?php

namespace App\Console\Commands;

use App\Models\OwnedMovie;
use App\SourceProviders\SourceManager;
use App\SourceProviders\Transfers\MovieTransfer;
use Astrotomic\Tmdb\Models\Collection;
use Astrotomic\Tmdb\Models\Movie;

class LoadPeopleCommand extends Command
{
    protected $signature = 'person:load';

    public function handle(): int
    {
        $movies = Movie::all();

        $bar = $this->startProgressBar($movies->count());

        $movies->each(fn (Movie $movie) => tap(
            rescue(fn() => $movie->credits()->all()),
            fn () => $bar->advance()
        ));

        $bar->finish();

        return self::SUCCESS;
    }
}
