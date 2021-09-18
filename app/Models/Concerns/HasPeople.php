<?php

namespace App\Models\Concerns;

use App\Models\Person;
use Staudenmeir\EloquentJsonRelations\Relations\BelongsToJson;

trait HasPeople
{
    protected function initializeHasPeople(): void
    {
        $this->withCasts(['person_ids' => 'array']);
    }

    public function people(): BelongsToJson
    {
        return $this->belongsToJson(Person::class, 'person_ids');
    }

    public function attachPerson(int $id, string $character): bool
    {
        $person = Person::query()->find($id);

        if ($person === null) {
            $person = Person::query()
                ->firstOrNew(['id' => $id])
                ->updateFromTmdb();
        }

        return $this->people()->attach($person)->save();
    }
}
