<?php

namespace App\Console\Commands;

use App\Models\OwnedMovie;
use App\SourceProviders\SourceManager;
use App\SourceProviders\Transfers\MovieTransfer;
use Astrotomic\Tmdb\Models\Movie;

class LoadMoviesCommand extends Command
{
    protected $signature = 'movie:load';

    public function handle(SourceManager $manager): int
    {
        $movies = $manager->driver()->movies();

        $bar = $this->startProgressBar($movies->count());

        $movies->each(fn (MovieTransfer $transfer) => tap(
            rescue(fn () => OwnedMovie::query()->updateOrCreate(
                [
                    'source' => $transfer->source,
                    'source_id' => $transfer->sourceId,
                ],
                [
                    'movie_id' => Movie::query()->findOrFail($transfer->tmdbId)->id,
                    'width' => $transfer->width,
                    'height' => $transfer->height,
                ]
            )),
            fn () => $bar->advance()
        ));

        $bar->finish();

        return self::SUCCESS;
    }
}
