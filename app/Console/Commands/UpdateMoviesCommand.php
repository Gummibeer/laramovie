<?php

namespace App\Console\Commands;

use App\Actions\LoadMovieCastFromTmdb;
use App\Models\Movie;
use Illuminate\Console\Command;

class UpdateMoviesCommand extends Command
{
    protected $signature = 'movie:update';

    public function handle(): int
    {
        $bar = $this->output->createProgressBar(Movie::query()->count());
        $bar->setFormat('very_verbose');
        $bar->start();

        Movie::query()->eachById(static function (Movie $movie) use ($bar): void {
            $movie->updateFromTmdb();
            app()->call(LoadMovieCastFromTmdb::class, [
                'movie' => $movie,
            ]);
            $bar->advance();
        });

        $bar->finish();

        return self::SUCCESS;
    }
}
