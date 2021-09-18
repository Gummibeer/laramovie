<?php

namespace App\Actions;

use App\Models\Season;
use App\Models\TvShow;

class LoadTvShowSeasonFromTmdb
{
    public function __invoke(TvShow $tvShow, int $number, array $attributes = []): Season
    {
        $season = $tvShow->seasons()
            ->where('number', $number)
            ->first();

        if ($season instanceof Season) {
            return $season;
        }

        $season = $tvShow->seasons()->firstOrNew(
            ['number' => $number],
            $attributes
        )->updateFromTmdb();

        return app()->call(LoadTvShowSeasonCastFromTmdb::class, [
            'season' => $season,
        ]);
    }
}
