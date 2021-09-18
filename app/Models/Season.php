<?php

namespace App\Models;

use App\Models\Concerns\HasGdriveFolder;
use App\Models\Concerns\HasPoster;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Http;

/**
 * App\Models\Season.
 *
 * @property int $id
 * @property int $tv_show_id
 * @property int $number
 * @property string $gdrive_id
 * @property string $name
 * @property string|null $overview
 * @property string|null $poster_path
 * @property string|null $released_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Season newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Season newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Season query()
 * @mixin \Illuminate\Database\Eloquent\Builder
 *
 * @property-read \App\Models\TvShow $tv_show
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Episode[] $episodes
 */
class Season extends Model
{
    use HasPoster;
    use HasGdriveFolder;

    public $incrementing = false;
    public $timestamps = false;

    protected $casts = [
        'id' => 'int',
        'tv_show_id' => 'int',
        'number' => 'int',
        'released_at' => 'date',
    ];

    public function fillFromTmdb(): self
    {
        $data = Http::tmdb()->get(sprintf('tv/%d/season/%d', $this->tv_show_id, $this->number), ['language' => app()->getLocale()])
            ->throw()
            ->json();

        return $this->fill([
            'id' => $data['id'],
            'name' => trim($data['name']),
            'overview' => $data['overview'] ?: $this->overview,
            'poster_path' => $data['poster_path'] ?: $this->poster_path,
            'released_at' => $data['air_date'] ?: $this->released_at,
        ]);
    }

    public function updateFromTmdb(): self
    {
        $this->fillFromTmdb()->save();

        return $this;
    }

    public function tv_show(): BelongsTo
    {
        return $this->belongsTo(TvShow::class);
    }

    public function episodes(): HasMany
    {
        return $this->hasMany(Episode::class);
    }

    public function tmdb_link(): string
    {
        return sprintf(
            '%s/%d/season/%d',
            'https://www.themoviedb.org/tv',
            $this->tv_show_id,
            $this->number
        );
    }
}
