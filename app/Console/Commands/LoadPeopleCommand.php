<?php

namespace App\Console\Commands;

use Astrotomic\Tmdb\Models\Movie;
use Astrotomic\Tmdb\Models\Person;

class LoadPeopleCommand extends Command
{
    protected $signature = 'person:load';

    public function handle(): int
    {
        $movies = Movie::all();

        $this->withProgressBar($movies, static function (Movie $movie): void {
            rescue(fn () => $movie->credits()->all());
        });

        $people = Person::all();

        $this->withProgressBar($people, static function (Person $person): void {
            rescue(fn () => $person->updateFromTmdb(with: ['movie_credits']));
        });

        return self::SUCCESS;
    }
}
