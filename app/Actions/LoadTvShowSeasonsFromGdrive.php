<?php

namespace App\Actions;

use App\Models\TvShow;
use Google\Service\Drive;

class LoadTvShowSeasonsFromGdrive
{
    public function __construct(
        protected Drive $drive,
    ) {
    }

    public function __invoke(TvShow $tvShow): TvShow
    {
        $folders = $this->drive->files->listFiles([
            'pageSize' => 1000,
            'fields' => 'files(id,name)',
            'spaces' => 'drive',
            'q' => sprintf('trashed = false and "%s" in parents and mimeType = "application/vnd.google-apps.folder"', $tvShow->gdrive_id),
        ])->getFiles();

        foreach ($folders as $folder) {
            rescue(fn () => app()->call(LoadTvShowSeasonFromGdrive::class, [
                'tvShow' => $tvShow,
                'folderId' => $folder->getId(),
            ]));
        }

        return $tvShow;
    }
}
