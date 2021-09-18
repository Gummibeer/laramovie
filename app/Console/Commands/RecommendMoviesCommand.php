<?php

namespace App\Console\Commands;

use App\Models\Movie;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class RecommendMoviesCommand extends Command
{
    protected $signature = 'movie:recommend';

    public function handle(): int
    {
        $bar = $this->output->createProgressBar(Movie::query()->count());
        $bar->setFormat('very_verbose');
        $bar->setRedrawFrequency(1);
        $bar->minSecondsBetweenRedraws(0);
        $bar->maxSecondsBetweenRedraws(1);
        $bar->start();

        $min = 0;
        $recommendations = Movie::query()
            ->get()
            ->map(static function (Movie $movie) use ($bar): Collection {
                $movies = Http::tmdb()->get(sprintf('movie/%d/recommendations', $movie->id), ['language' => app()->getLocale()])
                    ->throw()
                    ->collect('results');

                $bar->advance();

                return $movies;
            })
            ->collapse()
            ->groupBy('id')
            ->reject(fn (Collection $movies, int $id) => Movie::query()->whereKey($id)->exists())
            ->tap(function (Collection $collection) use (&$min): void {
                $min = $collection->max(fn (Collection $movies) => $movies->count()) / 2;
            })
            ->reject(fn (Collection $movies) => $movies->count() < $min)
            ->sortByDesc(fn (Collection $movies) => $movies->count())
            ->map->first();

        $bar->finish();

        File::put(storage_path('app/movies-recommended.json'), $recommendations->toJson(JSON_PRETTY_PRINT));

        return self::SUCCESS;
    }
}
