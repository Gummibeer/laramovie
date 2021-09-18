<?php

namespace App\Actions;

use App\Models\Season;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;
use Illuminate\Support\Str;

class LoadSeasonEpisodesFromGdrive
{
    public function __construct(
        protected Drive $drive,
    ) {
    }

    public function __invoke(Season $season): Season
    {
        $files = $this->drive->files->listFiles([
            'pageSize' => 1000,
            'fields' => 'files(name)',
            'spaces' => 'drive',
            'q' => sprintf('trashed = false and "%s" in parents and mimeType contains "video/"', $season->gdrive_id),
        ])->getFiles();

        collect($files)
            ->map(fn (DriveFile $file): string => $file->getName())
            ->map(fn (string $name): int => Str::match('/.*\sS0*'.$season->number.'E0*(\d+)\s.*/', $name))
            ->unique()
            ->sortBy(null)
            ->values()
            ->each(fn (int $number) => rescue(fn () => app()->call(LoadSeasonEpisodeFromTmdb::class, [
                'season' => $season,
                'number' => $number,
            ])));

        return $season;
    }
}
