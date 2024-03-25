<?php

namespace App\Console\Commands;

use App\Models\OwnedMovie;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;

class RecommendMoviesCommand extends Command
{
    protected $signature = 'movie:recommend';

    public function handle(): int
    {
        $bar = $this->output->createProgressBar(OwnedMovie::query()->count());
        $bar->setFormat('very_verbose');
        $bar->setRedrawFrequency(1);
        $bar->minSecondsBetweenRedraws(0);
        $bar->maxSecondsBetweenRedraws(1);
        $bar->start();

        $recommendations = OwnedMovie::query()
            ->cursor()
            ->map(static function (OwnedMovie $movie) use ($bar): Collection {
                $movies = collect()
                    ->concat(rescue(fn () => $movie->movie->recommendations(36)) ?? [])
                    ->concat(rescue(fn () => $movie->movie->collection?->movies) ?? []);

                $bar->advance();

                return $movies;
            })
            ->collapse()
            ->countBy('id')
            ->collect()
            ->reject(fn (int $count, int $id) => OwnedMovie::query()->where('movie_id', $id)->exists())
            ->sortDesc()
            ->keys();

        $bar->finish();

        File::put(storage_path('app/movies-recommended.json'), $recommendations->toJson(JSON_PRETTY_PRINT));

        return self::SUCCESS;
    }
}
