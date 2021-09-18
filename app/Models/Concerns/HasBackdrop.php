<?php

namespace App\Models\Concerns;

trait HasBackdrop
{
    public function backdrop(int $size = 1280): ?string
    {
        if (empty($this->backdrop_path)) {
            return null;
        }

        return sprintf(
            '%s/w%d/%s',
            'https://image.tmdb.org/t/p',
            trim($size, '/'),
            ltrim($this->backdrop_path, '/')
        );
    }
}
