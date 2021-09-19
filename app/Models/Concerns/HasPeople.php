<?php

namespace App\Models\Concerns;

use App\Models\Person;
use App\Workflows\LoadPerson;
use Staudenmeir\EloquentJsonRelations\Relations\BelongsToJson;

trait HasPeople
{
    protected function initializeHasPeople(): void
    {
        $this->withCasts(['cast_ids' => 'array']);
    }

    public function cast(): BelongsToJson
    {
        return $this->belongsToJson(Person::class, 'cast_ids');
    }

    public function crew(): BelongsToJson
    {
        return $this->belongsToJson(Person::class, 'crew_ids');
    }

    public function attachCast(int $tmdbId): bool
    {
        return $this->cast()->attach(
            Person::query()->find($tmdbId) ?? LoadPerson::run($tmdbId)
        )->save();
    }

    public function attachCrew(int $tmdbId): bool
    {
        return $this->crew()->attach(
            Person::query()->find($tmdbId) ?? LoadPerson::run($tmdbId)
        )->save();
    }
}
