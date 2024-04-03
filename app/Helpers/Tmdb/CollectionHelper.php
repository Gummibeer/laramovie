<?php

namespace App\Helpers\Tmdb;

use App\Models\OwnedMovie;
use Astrotomic\Tmdb\Enums\MovieStatus;
use Astrotomic\Tmdb\Models\Collection;
use Astrotomic\Tmdb\Models\Movie;
use Carbon\CarbonInterval;
use Illuminate\Support\Facades\Cache;

class CollectionHelper
{
    public static function make(Collection $collection): self
    {
        return new self($collection);
    }

    public function __construct(
        protected Collection $collection
    ) {
    }

    public function ownedMovieCount(): int
    {
        return Cache::remember(
            key: $this->key('owned_movie_count'),
            ttl: CarbonInterval::day(),
            callback: fn () => Movie::query()
                ->where('collection_id', $this->collection->id)
                ->whereIn('id', OwnedMovie::query()->distinct()->pluck('movie_id'))
                ->count()
        );
    }

    public function movieCount(): int
    {
        return Cache::remember(
            key: $this->key('movie_count'),
            ttl: CarbonInterval::day(),
            callback: fn () => Movie::query()
                ->where('collection_id', $this->collection->id)
                ->where('status', MovieStatus::RELEASED())
                ->count()
        );
    }

    public function percentage(): float
    {
        return Cache::remember(
            key: $this->key('percentage'),
            ttl: CarbonInterval::day(),
            callback: fn () => max(0, min($this->ownedMovieCount() / $this->movieCount() * 100, 100))
        );
    }

    protected function key(string $suffix): string
    {
        return "collection.{$this->collection->id}.{$suffix}";
    }
}
