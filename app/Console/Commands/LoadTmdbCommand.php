<?php

namespace App\Console\Commands;

use App\Models\OwnedMovie;
use App\SourceProviders\SourceManager;
use App\SourceProviders\Transfers\MovieTransfer;
use Astrotomic\Tmdb\Models\Movie;
use Astrotomic\Tmdb\Models\MovieGenre;
use Astrotomic\Tmdb\Models\WatchProvider;

class LoadTmdbCommand extends Command
{
    protected $signature = 'tmdb:load';

    public function handle(): int
    {
        MovieGenre::all();
        WatchProvider::all();

        return self::SUCCESS;
    }
}
