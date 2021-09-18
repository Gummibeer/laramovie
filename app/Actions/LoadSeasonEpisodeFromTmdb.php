<?php

namespace App\Actions;

use App\Models\Episode;
use App\Models\Season;

class LoadSeasonEpisodeFromTmdb
{
    public function __invoke(Season $season, int $number, array $attributes = []): Episode
    {
        $episode = $season->episodes()
            ->where('number', $number)
            ->first();

        if ($episode instanceof Episode) {
            return $episode;
        }

        $episode = $season->episodes()->firstOrNew(
            ['number' => $number],
            $attributes
        )->updateFromTmdb();

        return app()->call(LoadSeasonEpisodeCastFromTmdb::class, [
            'episode' => $episode,
        ]);
    }
}
