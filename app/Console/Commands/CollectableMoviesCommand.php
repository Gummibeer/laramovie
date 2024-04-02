<?php

namespace App\Console\Commands;

use App\Models\OwnedMovie;
use Astrotomic\Tmdb\Models\Movie;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;

class CollectableMoviesCommand extends Command
{
    protected $signature = 'movie:collectable';

    public function handle(): int
    {
        $movies = Movie::query()
            ->whereIn(
                'id',
                OwnedMovie::query()
                    ->distinct()
                    ->pluck('movie_id')
            )
            ->cursor();

        $bar = $this->output->createProgressBar($movies->count());
        $bar->setFormat('very_verbose');
        $bar->setRedrawFrequency(1);
        $bar->minSecondsBetweenRedraws(0);
        $bar->maxSecondsBetweenRedraws(1);
        $bar->start();

        $recommendations = $movies
            ->map(static function (Movie $movie) use ($bar): Collection {
                $movies = collect(rescue(fn () => $movie->collection?->movies) ?? []);

                $bar->advance();

                return $movies;
            })
            ->collapse()
            ->countBy('id')
            ->collect()
            ->except($movies->pluck('id'))
            ->keys();

        $bar->finish();

        File::put(storage_path('app/movies-collectable.json'), $recommendations->toJson(JSON_PRETTY_PRINT));

        return self::SUCCESS;
    }
}
