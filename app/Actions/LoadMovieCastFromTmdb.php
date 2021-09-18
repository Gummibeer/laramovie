<?php

namespace App\Actions;

use App\Models\Movie;
use Illuminate\Support\Facades\Http;

class LoadMovieCastFromTmdb
{
    public function __invoke(Movie $movie): Movie
    {
        $cast = Http::tmdb()->get(sprintf('movie/%d/credits', $movie->id), ['language' => app()->getLocale()])
            ->throw()
            ->json('cast');

        foreach ($cast as $actor) {
            $movie->attachPerson($actor['id'], $actor['character']);
        }

        return $movie;
    }
}
