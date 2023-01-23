<?php

namespace App\Models;

use App\SourceProviders\Contracts\Source;
use App\SourceProviders\SourceManager;
use Astrotomic\Tmdb\Models\Movie;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\OwnedMovie.
 *
 * @property int $id
 * @property int $movie_id
 * @property string $source
 * @property string $source_id
 * @property int $width
 * @property int $height
 * @property-read Movie $movie
 *
 * @method static \Illuminate\Database\Eloquent\Builder|OwnedMovie newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OwnedMovie newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OwnedMovie query()
 *
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class OwnedMovie extends Model
{
    public $timestamps = false;

    public function movie(): BelongsTo
    {
        return $this->belongsTo(Movie::class, 'movie_id', 'id');
    }

    public function url(): string
    {
        return $this->source()->url($this->source_id);
    }

    protected function source(): Source
    {
        return app(SourceManager::class)->driver($this->source);
    }
}
