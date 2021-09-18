<?php

namespace App\Console\Commands;

use App\Actions\LoadTvShowFromGdrive;
use App\Models\TvShow;
use Generator;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\LazyCollection;

class LoadTvShowsCommand extends Command
{
    protected $signature = 'tvshow:load';

    protected const ID = '1zJIKdiPyP3Ui767GJYHBZYJSMPWX2bg_';

    public function handle(Drive $drive): int
    {
        $folders = LazyCollection::make(static function () use ($drive): Generator {
            $nextPageToken = null;
            do {
                $files = $drive->files->listFiles([
                    'pageSize' => 1000,
                    'fields' => 'files(id,name,webViewLink),nextPageToken',
                    'spaces' => 'drive',
                    'q' => sprintf('trashed = false and "%s" in parents and mimeType = "application/vnd.google-apps.folder"', self::ID),
                    'pageToken' => $nextPageToken,
                ]);

                $nextPageToken = $files->getNextPageToken();

                yield from $files->getFiles();
            } while ($nextPageToken !== null);
        })->keyBy(fn (DriveFile $folder): string => $folder->getId())->collect();

        $bar = $this->output->createProgressBar($folders->count());
        $bar->setFormat('very_verbose');
        $bar->start();

        $tvShows = $folders->map(fn (DriveFile $folder): ?TvShow => tap(rescue(fn () => app()->call(LoadTvShowFromGdrive::class, [
            'folderId' => $folder->getId(),
        ])), fn () => $bar->advance()));

        $bar->finish();
        $this->line('');

        $missing = $folders->only($tvShows->reject()->keys());
        $tvShows = $tvShows->filter();

        if ($this->output->isVerbose()) {
            $this->output->table(
                ['name', 'link'],
                $missing->map(fn (DriveFile $folder): array => [
                    'name' => $folder->getName(),
                    'link' => $folder->getWebViewLink(),
                ])->sortBy('name')->all()
            );
        }

        File::put(storage_path('app/tvshows-missing.json'), $missing->map(fn (DriveFile $folder): array => [
            'name' => $folder->getName(),
            'link' => $folder->getWebViewLink(),
        ])->sortBy('name')->toJson(JSON_PRETTY_PRINT));

        $this->warn(sprintf(
            'Failed to import %d TV Shows',
            $missing->count()
        ));

        if ($this->output->isVeryVerbose()) {
            $this->output->listing(
                $tvShows->map(fn (TvShow $tvShow): string => $tvShow->name)->sortBy(null)->all()
            );
        }

        $this->info(sprintf(
            'Imported %d TV Shows',
            $tvShows->count()
        ));

        return self::SUCCESS;
    }
}
