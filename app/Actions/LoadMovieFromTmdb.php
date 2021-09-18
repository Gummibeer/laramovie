<?php

namespace App\Actions;

use App\Models\Movie;

class LoadMovieFromTmdb
{
    public function __invoke(int $tmdbId, array $attributes = []): Movie
    {
        $movie = Movie::query()->find($tmdbId);

        if ($movie instanceof Movie) {
            return $movie;
        }

        $movie = Movie::query()->firstOrNew(
            ['id' => $tmdbId],
            $attributes
        )->updateFromTmdb();

        return app()->call(LoadMovieCastFromTmdb::class, [
            'movie' => $movie,
        ]);
    }
}
