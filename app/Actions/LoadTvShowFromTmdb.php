<?php

namespace App\Actions;

use App\Models\TvShow;

class LoadTvShowFromTmdb
{
    public function __invoke(int $tmdbId, array $attributes = []): TvShow
    {
        $tvShow = TvShow::query()->find($tmdbId);

        if ($tvShow instanceof TvShow) {
            return $tvShow;
        }

        $tvShow = TvShow::query()->firstOrNew(
            ['id' => $tmdbId],
            $attributes
        )->updateFromTmdb();

        return app()->call(LoadTvShowCastFromTmdb::class, [
            'tvShow' => $tvShow,
        ]);
    }
}
