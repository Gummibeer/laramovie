<?php

namespace App\Console\Commands;

use App\Models\OwnedMovie;
use App\SourceProviders\SourceManager;
use App\SourceProviders\Transfers\MovieTransfer;
use Astrotomic\Tmdb\Models\Collection;
use Astrotomic\Tmdb\Models\Movie;

class LoadCollectionsCommand extends Command
{
    protected $signature = 'collection:load';

    public function handle(): int
    {
        $collections = Collection::all();

        $bar = $this->startProgressBar($collections->count());

        $collections->each(fn (Collection $collection) => tap(
            $collection->movies()->all(),
            fn () => $bar->advance()
        ));

        $bar->finish();

        return self::SUCCESS;
    }
}
