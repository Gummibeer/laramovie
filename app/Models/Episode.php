<?php

namespace App\Models;

use App\Models\Concerns\HasStill;
use Google\Service\Drive;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Http;

/**
 * App\Models\Episode.
 *
 * @property int $id
 * @property int $season_id
 * @property int $number
 * @property string $name
 * @property string|null $overview
 * @property string|null $still_path
 * @property \Carbon\Carbon|null $released_at
 * @property-read \App\Models\Season $season
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Episode newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Episode newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Episode query()
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Episode extends Model
{
    use HasStill;

    public $incrementing = false;
    public $timestamps = false;

    protected $casts = [
        'id' => 'int',
        'season_id' => 'int',
        'number' => 'int',
        'released_at' => 'date',
    ];

    public function fillFromTmdb(): self
    {
        $data = Http::tmdb()->get(sprintf('tv/%d/season/%d/episode/%d', $this->season->tv_show_id, $this->season->number, $this->number), ['language' => app()->getLocale()])
            ->throw()
            ->json();

        return $this->fill([
            'id' => $data['id'],
            'name' => trim($data['name']),
            'overview' => $data['overview'] ?: $this->overview,
            'still_path' => $data['still_path'] ?: $this->still_path,
            'released_at' => $data['air_date'] ?: $this->released_at,
        ]);
    }

    public function updateFromTmdb(): self
    {
        $this->fillFromTmdb()->save();

        return $this;
    }

    public function season(): BelongsTo
    {
        return $this->belongsTo(Season::class);
    }

    public function video_files(): array
    {
        return once(fn () => app(Drive::class)->files->listFiles([
            'pageSize' => 100,
            'fields' => 'files(id,name,mimeType,videoMediaMetadata,webViewLink)',
            'spaces' => 'drive',
            'q' => sprintf(
                'trashed = false and "%s" in parents and mimeType contains "video/" and name contains "%s"',
                $this->season->gdrive_id,
                sprintf(
                    'S%sE%s',
                    str_pad($this->season->number, 2, '0', STR_PAD_LEFT),
                    str_pad($this->number, 2, '0', STR_PAD_LEFT)
                )
            ),
        ])->getFiles());
    }
}
