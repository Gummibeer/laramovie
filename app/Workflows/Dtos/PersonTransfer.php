<?php

namespace App\Workflows\Dtos;

/**
 * @see \App\Models\Person
 */
class PersonTransfer
{
    public function __construct(
        public ?string $name = null,
        public ?int $tmdbId = null,
        public ?string $imdbId = null,
        public ?string $description = null,
        public ?string $posterPath = null,
    ) {
    }
}
