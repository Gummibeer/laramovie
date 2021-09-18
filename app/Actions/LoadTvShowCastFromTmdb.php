<?php

namespace App\Actions;

use App\Models\TvShow;
use Illuminate\Support\Facades\Http;

class LoadTvShowCastFromTmdb
{
    public function __invoke(TvShow $tvShow): TvShow
    {
        $cast = Http::tmdb()->get(sprintf('tv/%d/credits', $tvShow->id), ['language' => app()->getLocale()])
            ->throw()
            ->json('cast');

        foreach ($cast as $actor) {
            $tvShow->attachPerson($actor['id'], $actor['character']);
        }

        return $tvShow;
    }
}
