<?php

namespace App\Actions;

use App\Models\Episode;
use Illuminate\Support\Facades\Http;

class LoadSeasonEpisodeCastFromTmdb
{
    public function __invoke(Episode $episode): Episode
    {
        $cast = Http::tmdb()->get(sprintf('tv/%d/season/%d/episode/%d/credits', $episode->season->tv_show_id, $episode->season->number, $episode->number), ['language' => app()->getLocale()])
            ->throw()
            ->json('cast');

        foreach ($cast as $actor) {
            $episode->season->tv_show->attachPerson($actor['id'], $actor['character']);
        }

        return $episode;
    }
}
