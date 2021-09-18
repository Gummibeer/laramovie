<?php

namespace App\Actions;

use App\Exceptions\NoTmdbSearchResultsException;
use App\Exceptions\TmdbIdMissingException;
use App\Exceptions\TooManyTmdbSearchResultsException;
use App\Models\Movie;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class LoadMovieFromGdrive
{
    public function __construct(
        protected Drive $drive,
    ) {
    }

    public function __invoke(string $folderId): Movie
    {
        $movie = Movie::query()
            ->where('gdrive_id', $folderId)
            ->first();

        if ($movie instanceof Movie) {
            return $movie;
        }

        $file = Arr::first($this->drive->files->listFiles([
            'fields' => 'files(id)',
            'spaces' => 'drive',
            'q' => sprintf('trashed = false and "%s" in parents and name = "tmdbid.txt"', $folderId),
        ])->getFiles());

        if ($file instanceof DriveFile) {
            $tmdbId = trim(Storage::disk('gdrive')->get($file->getId()));
        }

        if (empty($tmdbId)) {
            $folder = $this->drive->files->get($folderId, [
                'fields' => 'name',
            ]);

            preg_match('/^(.+) \((\d+)\)$/', $folder->getName(), $hits);

            [, $name, $year] = $hits;

            $search = Http::tmdb()
                ->get('search/movie', [
                    'language' => app()->getLocale(),
                    'query' => $name,
                    'year' => $year,
                ])
                ->throw()
                ->json();

            if ($search['total_results'] === 0) {
                throw new NoTmdbSearchResultsException();
            } elseif ($search['total_results'] === 1) {
                $tmdbId = $search['results'][0]['id'];
            } else {
                $results = collect($search['results'])->filter(fn (array $hit): bool => $hit['original_title'] === $name);

                if ($results->count() === 1) {
                    $tmdbId = $results->first()['id'];
                } else {
                    $results = collect($search['results'])->filter(fn (array $hit): bool => $hit['title'] === $name);

                    if ($results->count() === 1) {
                        $tmdbId = $results->first()['id'];
                    } else {
                        throw new TooManyTmdbSearchResultsException();
                    }
                }
            }
        }

        if (empty($tmdbId)) {
            throw new TmdbIdMissingException();
        }

        if ($file === null) {
            $df = new DriveFile();
            $df->setName('tmdbid.txt');
            $df->setMimeType('text/text');
            $df->setParents([$folderId]);

            $file = $this->drive->files->create($df, [
                'fields' => 'id, name',
            ]);

            Storage::disk('gdrive')->put($file->getId(), $tmdbId);
        }

        return app()->call(LoadMovieFromTmdb::class, [
            'tmdbId' => (int) $tmdbId,
            'attributes' => [
                'gdrive_id' => $folderId,
            ],
        ]);
    }
}
