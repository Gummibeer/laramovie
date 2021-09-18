<?php

namespace App\Models;

use App\Models\Concerns\HasBackdrop;
use App\Models\Concerns\HasGdriveFolder;
use App\Models\Concerns\HasPeople;
use App\Models\Concerns\HasPoster;
use App\Models\Concerns\Searchable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Http;
use Staudenmeir\EloquentJsonRelations\HasJsonRelationships;

/**
 * App\Models\TvShow.
 *
 * @property int $id
 * @property string $gdrive_id
 * @property string $name
 * @property string|null $overview
 * @property string|null $backdrop_path
 * @property string|null $poster_path
 * @property \Carbon\Carbon|null $released_at
 * @property float $vote_average
 * @property string[] $genres
 *
 * @method static \Illuminate\Database\Eloquent\Builder|TvShow newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TvShow newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TvShow query()
 * @mixin \Illuminate\Database\Eloquent\Builder
 *
 * @property-read Collection|\App\Models\Person[] $people
 * @property-read Collection|\App\Models\Season[] $seasons
 */
class TvShow extends Model
{
    use Searchable;
    use HasPoster;
    use HasBackdrop;
    use HasGdriveFolder;
    use HasPeople;
    use HasJsonRelationships;

    public $incrementing = false;
    public $timestamps = false;

    protected $casts = [
        'id' => 'int',
        'released_at' => 'date',
        'vote_average' => 'float',
        'genres' => 'array',
    ];

    public function fillFromTmdb(): self
    {
        $data = Http::tmdb()->get(sprintf('tv/%d', $this->id), ['language' => app()->getLocale()])
            ->throw()
            ->json();

        return $this->fill([
            'name' => trim($data['name']),
            'overview' => $data['overview'] ?: $this->overview,
            'backdrop_path' => $data['backdrop_path'] ?: $this->backdrop_path,
            'poster_path' => $data['poster_path'] ?: $this->poster_path,
            'released_at' => $data['first_air_date'] ?: $this->released_at,
            'vote_average' => $data['vote_average'] ?: $this->vote_average ?? 0,
            'genres' => array_column($data['genres'], 'name'),
        ]);
    }

    public function updateFromTmdb(): self
    {
        $this->fillFromTmdb()->save();

        return $this;
    }

    public function seasons(): HasMany
    {
        return $this->hasMany(Season::class)
            ->orderBy('number');
    }

    public function recommendations(): Collection
    {
        return once(fn () => static::query()
            ->whereIn(
                'id',
                Http::tmdb()->get(sprintf('tv/%d/recommendations', $this->id), ['language' => app()->getLocale()])
                    ->throw()
                    ->collect('results')
                    ->pluck('id')
            )
            ->limit(12)
            ->orderBy('name')
            ->get());
    }

    public function tmdb_link(): string
    {
        return sprintf(
            '%s/%d',
            'https://www.themoviedb.org/tv',
            $this->id
        );
    }
}
