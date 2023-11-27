<?php

namespace App\Console\Commands;

use Astrotomic\Tmdb\Models\Person;

class LoadPeopleCreditsCommand extends Command
{
    protected $signature = 'person:credits';

    public function handle(): int
    {
        $people = Person::all();

        $this->withProgressBar($people, static function (Person $person): void {
            rescue(fn () => $person->updateFromTmdb(with: ['movie_credits']));
        });

        return self::SUCCESS;
    }
}
