<?php

namespace App\Console\Commands;

use App\Actions\LoadMovieFromGdrive;
use App\Models\Movie;
use Generator;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\LazyCollection;

class LoadMoviesCommand extends Command
{
    protected $signature = 'movie:load';

    protected const ID = '1DTorciZkxN_skbw_a9eXePGrZnRsnMVw';

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

        $movies = $folders->map(fn (DriveFile $folder): ?Movie => tap(rescue(fn () => app()->call(LoadMovieFromGdrive::class, [
            'folderId' => $folder->getId(),
        ])), fn () => $bar->advance()));

        $bar->finish();
        $this->line('');

        $missing = $folders->only($movies->reject()->keys());
        $movies = $movies->filter();

        if ($this->output->isVerbose()) {
            $this->output->table(
                ['name', 'link'],
                $missing->map(fn (DriveFile $folder): array => [
                    'name' => $folder->getName(),
                    'link' => $folder->getWebViewLink(),
                ])->sortBy('name')->all()
            );
        }

        File::put(storage_path('app/movies-missing.json'), $missing->map(fn (DriveFile $folder): array => [
            'name' => $folder->getName(),
            'link' => $folder->getWebViewLink(),
        ])->sortBy('name')->toJson(JSON_PRETTY_PRINT));

        $this->warn(sprintf(
            'Failed to import %d movies',
            $missing->count()
        ));

        if ($this->output->isVeryVerbose()) {
            $this->output->listing(
                $movies->map(fn (Movie $movie): string => $movie->name)->sortBy(null)->all()
            );
        }

        $this->info(sprintf(
            'Imported %d movies',
            $movies->count()
        ));

        return self::SUCCESS;
    }
}
