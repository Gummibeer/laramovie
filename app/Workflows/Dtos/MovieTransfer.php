<?php

namespace App\Workflows\Dtos;

use Carbon\Carbon;

/**
 * @see \App\Models\Movie
 */
class MovieTransfer extends Transfer
{
    public function __construct(
        public string $disk,
        public string $directory,
        public ?string $name = null,
        public ?int $year = null,
        public ?int $tmdbId = null,
        public ?string $imdbId = null,
        public ?string $description = null,
        public ?string $posterPath = null,
        public ?string $backdropPath = null,
        public ?Carbon $releasedAt = null,
        public ?int $runtime = null,
        public ?float $voteAverage = null,
        public ?array $genres = null,
        public ?array $cast = null,
        public ?array $crew = null,
    ) {
    }
}
