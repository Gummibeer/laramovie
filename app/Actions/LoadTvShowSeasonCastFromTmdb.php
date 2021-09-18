<?php

namespace App\Actions;

use App\Models\Season;
use Illuminate\Support\Facades\Http;

class LoadTvShowSeasonCastFromTmdb
{
    public function __invoke(Season $season): Season
    {
        $cast = Http::tmdb()->get(sprintf('tv/%d/season/%d/credits', $season->tv_show_id, $season->number), ['language' => app()->getLocale()])
            ->throw()
            ->json('cast');

        foreach ($cast as $actor) {
            $season->tv_show->attachPerson($actor['id'], $actor['character']);
        }

        return $season;
    }
}
