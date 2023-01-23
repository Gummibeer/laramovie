<?php

namespace App\Console\Commands;

use Astrotomic\Tmdb\Models\Collection;

class LoadCollectionsCommand extends Command
{
    protected $signature = 'collection:load';

    public function handle(): int
    {
        $collections = Collection::all();

        $this->withProgressBar($collections, static function (Collection $collection): void {
            rescue(fn () => $collection->movies()->all());
        });

        return self::SUCCESS;
    }
}
