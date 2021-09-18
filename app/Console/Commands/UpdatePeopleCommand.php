<?php

namespace App\Console\Commands;

use App\Models\Movie;
use App\Models\Person;
use Illuminate\Console\Command;

class UpdatePeopleCommand extends Command
{
    protected $signature = 'person:update';

    public function handle(): int
    {
        $bar = $this->output->createProgressBar(Person::query()->count());
        $bar->setFormat('very_verbose');
        $bar->start();

        Person::query()->eachById(static function (Movie $movie) use ($bar): void {
            $movie->updateFromTmdb();

            $bar->advance();
        });

        $bar->finish();

        return self::SUCCESS;
    }
}
