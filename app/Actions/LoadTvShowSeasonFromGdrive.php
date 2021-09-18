<?php

namespace App\Actions;

use App\Models\Season;
use App\Models\TvShow;
use Google\Service\Drive;
use Illuminate\Support\Str;

class LoadTvShowSeasonFromGdrive
{
    public function __construct(
        protected Drive $drive,
    ) {
    }

    public function __invoke(TvShow $tvShow, string $folderId): Season
    {
        $season = $tvShow->seasons()
            ->where('gdrive_id', $folderId)
            ->first();

        if ($season instanceof Season) {
            return $season;
        }

        $folder = $this->drive->files->get($folderId, [
            'fields' => 'id,name',
        ]);

        $number = (int) (string) Str::of($folder->getName())
            ->lower()
            ->replace('season', '')
            ->trim();

        $season = app()->call(LoadTvShowSeasonFromTmdb::class, [
            'tvShow' => $tvShow,
            'number' => $number,
            'attributes' => [
                'gdrive_id' => $folder->getId(),
            ],
        ]);

        return app()->call(LoadSeasonEpisodesFromGdrive::class, [
            'season' => $season,
        ]);
    }
}
