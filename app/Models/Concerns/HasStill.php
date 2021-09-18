<?php

namespace App\Models\Concerns;

trait HasStill
{
    public function still(int $size = 1280): ?string
    {
        if (empty($this->still_path)) {
            return null;
        }

        return sprintf(
            '%s/w%d/%s',
            'https://image.tmdb.org/t/p',
            trim($size, '/'),
            ltrim($this->still_path, '/')
        );
    }
}
