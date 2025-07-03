<?php

namespace App\SourceProviders\Transfers;

class MovieTransfer
{
    public function __construct(
        public string $source,
        public string $sourceId,
        public int $tmdbId,
        public int $width,
        public int $height,
    ) {}
}
