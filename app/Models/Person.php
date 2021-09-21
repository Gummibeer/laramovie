<?php

namespace App\Models;

use App\Models\Concerns\HasPoster;
use App\Models\Concerns\Searchable;
use Illuminate\Support\Facades\Http;
use Staudenmeir\EloquentJsonRelations\HasJsonRelationships;
use Staudenmeir\EloquentJsonRelations\Relations\HasManyJson;

/**
 * App\Models\Person.
 *
 * @property int $id
 * @property string|null $imdb_id
 * @property string $name
 * @property string|null $biography
 * @property string|null $poster_path
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Movie[] $movies
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Person newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Person newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Person query()
 * @mixin \Illuminate\Database\Eloquent\Builder
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\TvShow[] $tv_shows
 * @property string|null $description
 */
class Person extends Model
{
    use Searchable;
    use HasPoster;
    use HasJsonRelationships;

    public $incrementing = false;
    public $timestamps = false;

    protected $casts = [
        'id' => 'int',
    ];

    public function fillFromTmdb(): self
    {
        $data = Http::tmdb()->get(sprintf('person/%d', $this->id), ['language' => app()->getLocale()])
            ->throw()
            ->json();

        return $this->fill([
            'name' => trim($data['name']),
            'imdb_id' => $data['imdb_id'] ?: $this->imdb_id,
            'poster_path' => $data['profile_path'] ?: $this->poster_path,
            'biography' => $data['biography'] ?: $this->biography,
        ]);
    }

    public function updateFromTmdb(): self
    {
        $this->fillFromTmdb()->save();

        return $this;
    }

    public function movies(): HasManyJson
    {
        return $this->hasManyJson(Movie::class, 'person_ids');
    }

    public function tv_shows(): HasManyJson
    {
        return $this->hasManyJson(TvShow::class, 'person_ids');
    }

    public function tmdb_link(): string
    {
        return sprintf(
            '%s/%d',
            'https://www.themoviedb.org/person',
            $this->id
        );
    }

    public function imdb_link(): ?string
    {
        if ($this->imdb_id === null) {
            return null;
        }

        return sprintf(
            '%s/%s',
            'https://www.imdb.com/name',
            $this->imdb_id
        );
    }
}
