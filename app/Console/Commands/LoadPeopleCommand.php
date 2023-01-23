<?php

namespace App\Console\Commands;

use Astrotomic\Tmdb\Models\Movie;

class LoadPeopleCommand extends Command
{
    protected $signature = 'person:load';

    public function handle(): int
    {
        $movies = Movie::all();

        $this->withProgressBar($movies, static function (Movie $movie): void {
            rescue(fn () => $movie->credits()->all());
        });

        return self::SUCCESS;
    }
}
