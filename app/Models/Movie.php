<?php

namespace App\Models;

use App\Models\Concerns\HasBackdrop;
use App\Models\Concerns\HasPeople;
use App\Models\Concerns\HasPoster;
use App\Models\Concerns\Searchable;
use Carbon\CarbonInterval;
use Illuminate\Contracts\Filesystem\Filesystem as FilesystemContract;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
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
 * @property-read EloquentCollection|\App\Models\Person[] $people
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Movie newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Movie newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Movie query()
 * @mixin \Illuminate\Database\Eloquent\Builder
 *
 * @property string $disk
 * @property string $directory
 * @property string|null $description
 * @property array $cast_ids
 * @property array $crew_ids
 */
class Movie extends Model
{
    use Searchable;
    use HasPoster;
    use HasBackdrop;
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

    public function recommendations(): EloquentCollection
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

    public function videos(): Collection
    {
        return collect($this->disk()->files($this->directory))
            ->filter(fn (string $filepath): bool => str_starts_with($this->disk()->mimeType($filepath), 'video/'))
            ->reject(fn (string $filepath): bool => str_contains(basename($filepath), 'trailer.'))
            ->map(fn (string $filepath): array => [
                'filepath' => $filepath,
                'filename' => basename($filepath),
                'mimetype' => $this->disk()->mimeType($filepath),
                'size' => $this->disk()->size($filepath),
                'video_format' => match (true) {
                    str_contains($filepath, '2160p') => '2160p',
                    str_contains($filepath, '1080p') => '1080p',
                    str_contains($filepath, '720p') => '720p',
                    str_contains($filepath, '480p') => '480p',
                    default => 'unknown',
                },
                'link' => URL::signedRoute('stream', [
                    'disk' => $this->disk,
                    'filepath' => base64_encode($filepath),
                ]),
            ]);
    }

    public function disk(): FilesystemContract
    {
        return Storage::disk($this->disk);
    }
}
