<?php

namespace App\Http\Controllers;

use App\Helpers\Tmdb\CollectionHelper;
use App\Models\OwnedMovie;
use Astrotomic\Tmdb\Enums\MovieStatus;
use Astrotomic\Tmdb\Models\Movie;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MovieController
{
    public function index(): ViewContract
    {
        $movies = Movie::query()
            ->whereIn('id', OwnedMovie::query()->distinct()->pluck('movie_id'))
            ->get()
            ->groupBy(fn (Movie $movie): string => Str::firstAlpha($movie->title))
            ->sortKeys()
            ->map(fn (Collection $movies) => $movies->sortBy(fn (Movie $movie) => Str::ascii($movie->title))->values());

        return view('movies', [
            'movies' => $movies,
        ]);
    }

    public function show(Movie $movie): ViewContract
    {
        return view('movie', [
            'movie' => $movie,
            'videos' => OwnedMovie::query()->where('movie_id', $movie->id)->get(),
        ]);
    }

    public function trending(): ViewContract
    {
        $movies = Movie::trending(60)
            ->reject(fn (Movie $movie) => OwnedMovie::query()->where('movie_id', $movie->id)->exists());

        return view('trending', [
            'movies' => $movies,
        ]);
    }

    public function toprated(): ViewContract
    {
        $movies = Movie::toprated(60)
            ->reject(fn (Movie $movie) => OwnedMovie::query()->where('movie_id', $movie->id)->exists());

        return view('toprated', [
            'movies' => $movies,
        ]);
    }

    public function upcoming(): ViewContract
    {
        $movies = Movie::upcoming(60)
            ->reject(fn (Movie $movie) => OwnedMovie::query()->where('movie_id', $movie->id)->exists());

        return view('upcoming', [
            'movies' => $movies,
        ]);
    }

    public function popular(): ViewContract
    {
        $movies = Movie::popular(60)
            ->reject(fn (Movie $movie) => OwnedMovie::query()->where('movie_id', $movie->id)->exists());

        return view('popular', [
            'movies' => $movies,
        ]);
    }

    public function recommend(): ViewContract
    {
        $movies = Movie::query()
            ->where('status', MovieStatus::RELEASED())
            ->whereKey(File::collect(storage_path('app/movies-recommended.json')))
            ->limit(180)
            ->get();

        return view('recommend', [
            'movies' => $movies,
        ]);
    }

    public function collectable(): ViewContract
    {
        $movies = Movie::query()
            ->where('status', MovieStatus::RELEASED())
            ->whereKey(File::collect(storage_path('app/movies-collectable.json')))
            ->orderBy('collection_id')
            ->orderBy('release_date')
            ->orderBy('original_title')
            ->get();

        $collections = \Astrotomic\Tmdb\Models\Collection::query()
            ->whereIn(
                'id',
                Movie::query()
                    ->whereIn('id', OwnedMovie::query()->distinct()->pluck('movie_id'))
                    ->distinct()
                    ->pluck('collection_id')
            )
            ->get()
            ->sortBy(fn (\Astrotomic\Tmdb\Models\Collection $collection) => round(CollectionHelper::make($collection)->percentage()).' // '.$collection->name);

        $completed = $collections
            ->filter(fn (\Astrotomic\Tmdb\Models\Collection $collection) => CollectionHelper::make($collection)->percentage() >= 100)
            ->values();

        return view('collectable', [
            'movies' => $movies,
            'collections' => $collections,
            'completed' => $completed,
            'movieCount' => [
                'owned' => $collections->sum(fn (\Astrotomic\Tmdb\Models\Collection $collection) => CollectionHelper::make($collection)->ownedMovieCount()),
                'total' => $collections->sum(fn (\Astrotomic\Tmdb\Models\Collection $collection) => CollectionHelper::make($collection)->movieCount()),
            ],
        ]);
    }
}
