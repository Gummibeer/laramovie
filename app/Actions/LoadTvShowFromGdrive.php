<?php

namespace App\Actions;

use App\Exceptions\NoTmdbSearchResultsException;
use App\Exceptions\TmdbIdMissingException;
use App\Exceptions\TooManyTmdbSearchResultsException;
use App\Models\TvShow;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class LoadTvShowFromGdrive
{
    public function __construct(
        protected Drive $drive,
    ) {
    }

    public function __invoke(string $folderId): TvShow
    {
        $tvShow = TvShow::query()
            ->where('gdrive_id', $folderId)
            ->first();

        if ($tvShow instanceof TvShow) {
            return $tvShow;
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

            if (preg_match('/^(.+) \((\d+)\)$/', $folder->getName(), $hits)) {
                [, $name, $year] = $hits;
            } else {
                $name = $folder->getName();
                $year = null;
            }

            $search = Http::tmdb()
                ->get('search/tv', [
                    'language' => app()->getLocale(),
                    'query' => $name,
                    'first_air_date_year' => $year,
                ])
                ->throw()
                ->json();

            if ($search['total_results'] === 0) {
                throw new NoTmdbSearchResultsException();
            } elseif ($search['total_results'] === 1) {
                $tmdbId = $search['results'][0]['id'];
            } else {
                $results = collect($search['results'])->filter(fn (array $hit): bool => $hit['original_name'] === $name);

                if ($results->count() === 1) {
                    $tmdbId = $results->first()['id'];
                } else {
                    $results = collect($search['results'])->filter(fn (array $hit): bool => $hit['name'] === $name);

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

        $tvShow = app()->call(LoadTvShowFromTmdb::class, [
            'tmdbId' => (int) $tmdbId,
            'attributes' => [
                'gdrive_id' => $folderId,
            ],
        ]);

        return app()->call(LoadTvShowSeasonsFromGdrive::class, [
            'tvShow' => $tvShow,
        ]);
    }
}
