<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class MovieController
{
    public function index(): ViewContract
    {
        return view('movies');
    }

    public function popular(): ViewContract
    {
        $movies = Http::tmdb()->get('/movie/popular', ['language' => app()->getLocale(), 'region' => 'DE'])
            ->throw()
            ->collect('results')
            ->reject(fn (array $data) => Movie::query()->whereKey($data['id'])->exists());

        return view('popular', [
            'movies' => $this->mapTmdbCollectionToMovies($movies),
        ]);
    }

    public function recommend(): ViewContract
    {
        $movies = File::collect(storage_path('app/movies-recommended.json'));

        return view('recommend', [
            'movies' => $this->mapTmdbCollectionToMovies($movies),
        ]);
    }

    public function unmapped(): ViewContract
    {
        $folders = File::collect(storage_path('app/movies-missing.json'))
            ->map(function (array $data, string $id) {
                preg_match('/^(.+) \((\d+)\)$/', $data['name'], $hits);
                [, $name, $year] = $hits;

                return array_merge($data, [
                    'id' => $id,
                    'movie' => [
                        'name' => $name,
                        'year' => $year,
                    ],
                ]);
            });

        return view('unmapped', [
            'folders' => $folders,
        ]);
    }

    public function show(Movie $movie): ViewContract
    {
        return view('movie', [
            'movie' => $movie,
        ]);
    }

    private function mapTmdbCollectionToMovies(Collection $results): Collection
    {
        return $results
            ->map(function (array $data): Movie {
                return new Movie([
                    'id' => $data['id'],
                    'name' => trim($data['title']),
                    'poster_path' => $data['poster_path'] ?: null,
                    'released_at' => $data['release_date'] ?: null,
                ]);
            })
            ->sortBy('name');
    }
}
