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

    public function resolution(): string
    {
        $resolutions = [
            ['width' => 3840, 'height' => 2160, 'label' => '2160p'],
            ['width' => 2560, 'height' => 1440, 'label' => '1440p'],
            ['width' => 1920, 'height' => 1080, 'label' => '1080p'],
            ['width' => 1280, 'height' => 720, 'label' => '720p'],
            ['width' => 854, 'height' => 480, 'label' => '480p'],
            ['width' => 720, 'height' => 576, 'label' => '576p'],
            ['width' => 640, 'height' => 360, 'label' => '360p'],
            ['width' => 426, 'height' => 240, 'label' => '240p'],
            ['width' => 320, 'height' => 180, 'label' => '180p'],
        ];

        // Iterate through resolutions and find the best match
        $bestMatch = null;
        $minDifference = PHP_INT_MAX;

        foreach ($resolutions as $resolution) {
            $difference = abs($resolution['width'] - $this->width) + abs($resolution['height'] - $this->height);

            if ($difference < $minDifference) {
                $bestMatch = $resolution['label'];
                $minDifference = $difference;
            }
        }

        return $bestMatch;
    }
}
