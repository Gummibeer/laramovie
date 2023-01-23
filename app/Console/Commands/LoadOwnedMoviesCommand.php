<?php

namespace App\Console\Commands;

use App\Models\OwnedMovie;
use App\SourceProviders\SourceManager;
use App\SourceProviders\Transfers\MovieTransfer;
use Astrotomic\Tmdb\Models\Movie;
use Illuminate\Console\Command;

class LoadOwnedMoviesCommand extends Command
{
    protected $signature = 'owned-movie:load {source?}';
    protected $description = 'Command description';

    public function handle(SourceManager $manager): int
    {
        $source = $manager->driver($this->argument('source'));

        $movies = $source->movies();

        $this->withProgressBar($movies, static function (MovieTransfer $transfer): void {
            rescue(fn () => OwnedMovie::query()->updateOrCreate([
                'source' => $transfer->source,
                'source_id' => $transfer->sourceId,
            ], [
                'movie_id' => Movie::query()->findOrFail($transfer->tmdbId)->id,
                'width' => $transfer->width,
                'height' => $transfer->height,
            ]));
        });

        OwnedMovie::query()
            ->where('source', $movies->first()->source)
            ->whereNotIn('source_id', $movies->pluck('sourceId'))
            ->delete();

        return self::SUCCESS;
    }
}
