<?php

namespace App\Workflows;

use App\Models\Movie;
use App\Workflows\Dtos\MovieTransfer;
use App\Workflows\Pipes\CheckTmdbidFile;
use App\Workflows\Pipes\GetTmdbMovie;
use App\Workflows\Pipes\ParseFoldername;
use App\Workflows\Pipes\SearchMovie;
use Illuminate\Pipeline\Pipeline;

class LoadMovie extends Pipeline
{
    protected $pipes = [
        CheckTmdbidFile::class,
        ParseFoldername::class,
        SearchMovie::class,
        GetTmdbMovie ::class,
    ];

    public static function make(string $disk, string $directory): self
    {
        return app(static::class)
            ->send(new MovieTransfer(
                disk: $disk,
                directory: $directory,
            ));
    }

    public static function run(string $disk, string $directory): Movie
    {
        return static::make($disk, $directory)
            ->then(static function (MovieTransfer $transfer): Movie {
                $movie = Movie::query()->updateOrCreate(
                    [
                        'id' => $transfer->tmdbId,
                        'disk' => $transfer->disk,
                    ],
                    [
                        'directory' => $transfer->directory,
                        'name' => $transfer->name,
                        'description' => $transfer->description ?: null,
                        'released_at' => $transfer->releasedAt ?: null,
                        'imdb_id' => $transfer->imdbId ?: null,
                        'poster_path' => $transfer->posterPath ?: null,
                        'backdrop_path' => $transfer->backdropPath ?: null,
                        'runtime' => $transfer->runtime ?: null,
                        'vote_average' => $transfer->voteAverage ?: 0,
                        'genres' => $transfer->genres ?: [],
                    ]
                );

                foreach ($transfer->cast as $castId) {
                    rescue(fn () => $movie->attachCast($castId));
                }

                foreach ($transfer->crew as $crewId) {
                    rescue(fn () => $movie->attachCrew($crewId));
                }

                return $movie;
            });
    }
}
