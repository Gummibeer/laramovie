<?php

namespace App\Console\Commands;

use App\Actions\LoadSeasonEpisodeCastFromTmdb;
use App\Actions\LoadSeasonEpisodesFromGdrive;
use App\Actions\LoadTvShowCastFromTmdb;
use App\Actions\LoadTvShowSeasonCastFromTmdb;
use App\Actions\LoadTvShowSeasonsFromGdrive;
use App\Models\Episode;
use App\Models\Season;
use App\Models\TvShow;
use Illuminate\Console\Command;

class UpdateTvShowsCommand extends Command
{
    protected $signature = 'tvshow:update';

    public function handle(): int
    {
        $bar = $this->output->createProgressBar(array_sum([
            TvShow::query()->count(),
            Season::query()->count(),
            Episode::query()->count(),
        ]));
        $bar->setFormat('very_verbose');
        $bar->start();

        TvShow::query()->eachById(static function (TvShow $tvShow) use ($bar): void {
            $tvShow->updateFromTmdb();
            app()->call(LoadTvShowCastFromTmdb::class, [
                'tvShow' => $tvShow,
            ]);
            app()->call(LoadTvShowSeasonsFromGdrive::class, [
                'tvShow' => $tvShow,
            ]);
            $bar->advance();

            $tvShow->seasons()->eachById(static function (Season $season) use ($bar): void {
                $season->updateFromTmdb();
                app()->call(LoadTvShowSeasonCastFromTmdb::class, [
                    'season' => $season,
                ]);
                app()->call(LoadSeasonEpisodesFromGdrive::class, [
                    'season' => $season,
                ]);
                $bar->advance();

                $season->episodes()->eachById(static function (Episode $episode) use ($bar): void {
                    $episode->updateFromTmdb();
                    app()->call(LoadSeasonEpisodeCastFromTmdb::class, [
                        'episode' => $episode,
                    ]);
                    $bar->advance();
                });
            });
        });

        $bar->finish();

        return self::SUCCESS;
    }
}
