<?php

namespace App\Http\Controllers;

use App\Models\OwnedMovie;
use Astrotomic\Tmdb\Models\Movie;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Support\Facades\File;

class MovieController
{
    public function index(): ViewContract
    {
        return view('movies');
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
        $movies = Movie::query()->findMany(
            ids: File::collect(storage_path('app/movies-recommended.json'))
        )->reject(fn (Movie $movie) => OwnedMovie::query()->where('movie_id', $movie->id)->exists());

        return view('recommend', [
            'movies' => $movies,
        ]);
    }
}
