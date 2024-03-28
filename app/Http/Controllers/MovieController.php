<?php

namespace App\Http\Controllers;

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
            ->findMany(File::collect(storage_path('app/movies-recommended.json')))
            ->take(120)
            ->values();

        return view('recommend', [
            'movies' => $movies,
        ]);
    }
}
