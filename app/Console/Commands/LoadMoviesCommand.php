<?php

namespace App\Console\Commands;

use Astrotomic\Tmdb\Models\Movie;

class LoadMoviesCommand extends Command
{
    protected $signature = 'movie:load';

    public function handle(): int
    {
        $movies = Movie::all();

        $this->withProgressBar($movies, static function (Movie $movie): void {
            rescue(fn () => $movie->updateFromTmdb());
        });

        return self::SUCCESS;
    }
}
