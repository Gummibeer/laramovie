<?php

namespace App\Workflows;

use App\Models\Person;
use App\Workflows\Dtos\PersonTransfer;
use App\Workflows\Pipes\GetTmdbPerson;
use Illuminate\Pipeline\Pipeline;

class LoadPerson extends Pipeline
{
    protected $pipes = [
        GetTmdbPerson ::class,
    ];

    public static function make(int $tmdbId): self
    {
        return app(static::class)
            ->send(new PersonTransfer(
                tmdbId: $tmdbId,
            ));
    }

    public static function run(int $tmdbId): Person
    {
        return static::make($tmdbId)
            ->then(static function (PersonTransfer $transfer): Person {
                return Person::query()->updateOrCreate(
                    [
                        'id' => $transfer->tmdbId,
                    ],
                    [
                        'name' => $transfer->name,
                        'description' => $transfer->description,
                        'imdb_id' => $transfer->imdbId,
                        'poster_path' => $transfer->posterPath,
                    ]
                );
            });
    }
}
