<?php

namespace App\Models;

use App\Models\Concerns\HasBackdrop;
use App\Models\Concerns\HasGdriveFolder;
use App\Models\Concerns\HasPeople;
use App\Models\Concerns\HasPoster;
use App\Models\Concerns\Searchable;
use Carbon\CarbonInterval;
use Google\Service\Drive;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Http;
use Staudenmeir\EloquentJsonRelations\HasJsonRelationships;

/**
 * App\Models\Movie.
 *
 * @property int $id
 * @property string $gdrive_id
 * @property string|null $imdb_id
 * @property string $name
 * @property string $overview
 * @property string|null $backdrop_path
 * @property string|null $poster_path
 * @property \Carbon\Carbon $released_at
 * @property int|null $runtime
 * @property float $vote_average
 * @property string[] $genres
 * @property-read Collection|\App\Models\Person[] $people
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Movie newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Movie newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Movie query()
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Movie extends Model
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
        'runtime' => 'int',
        'vote_average' => 'float',
        'genres' => 'array',
    ];

    public function fillFromTmdb(): self
    {
        $data = Http::tmdb()->get(sprintf('movie/%d', $this->id), ['language' => app()->getLocale()])
            ->throw()
            ->json();

        return $this->fill([
            'name' => trim($data['title']),
            'imdb_id' => $data['imdb_id'] ?: $this->imdb_id,
            'overview' => $data['overview'] ?: $this->overview,
            'backdrop_path' => $data['backdrop_path'] ?: $this->backdrop_path,
            'poster_path' => $data['poster_path'] ?: $this->poster_path,
            'released_at' => $data['release_date'] ?: $this->released_at,
            'runtime' => $data['runtime'] ?: $this->runtime,
            'vote_average' => $data['vote_average'] ?: $this->vote_average ?? 0,
            'genres' => array_column($data['genres'], 'name'),
        ]);
    }

    public function updateFromTmdb(): self
    {
        $this->fillFromTmdb()->save();

        return $this;
    }

    public function recommendations(): Collection
    {
        return once(fn () => static::query()
            ->whereIn(
                'id',
                Http::tmdb()->get(sprintf('movie/%d/recommendations', $this->id), ['language' => app()->getLocale()])
                    ->throw()
                    ->collect('results')
                    ->pluck('id')
            )
            ->limit(12)
            ->orderBy('name')
            ->get());
    }

    public function runtime(): ?CarbonInterval
    {
        if ($this->runtime === null) {
            return null;
        }

        return CarbonInterval::minutes($this->runtime)->cascade();
    }

    public function tmdb_link(): string
    {
        return sprintf(
            '%s/%d',
            'https://www.themoviedb.org/movie',
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
            'https://www.imdb.com/title',
            $this->imdb_id
        );
    }

    public function video_files(): array
    {
        return once(fn () => app(Drive::class)->files->listFiles([
            'pageSize' => 100,
            'fields' => 'files(id,name,mimeType,videoMediaMetadata,webViewLink)',
            'spaces' => 'drive',
            'q' => sprintf('trashed = false and "%s" in parents and mimeType contains "video/"', $this->gdrive_id),
        ])->getFiles());
    }
}
