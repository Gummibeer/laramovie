<?php

namespace App\Models\Concerns;

trait HasPoster
{
    public function poster(int $size = 780): string
    {
        if (empty($this->poster_path)) {
            return sprintf(
                '%s/%dx%d/9ca3af/ffffff.jpg?text=%s',
                'https://via.placeholder.com',
                $size,
                $size / 2 * 3,
                urlencode($this->name)
            );
        }

        return sprintf(
            '%s/w%d/%s',
            'https://image.tmdb.org/t/p',
            trim($size, '/'),
            ltrim($this->poster_path, '/')
        );
    }
}
