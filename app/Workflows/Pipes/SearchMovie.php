<?php

namespace App\Workflows\Pipes;

use App\Workflows\Contracts\Pipe;
use App\Workflows\Dtos\MovieTransfer;
use Carbon\Carbon;
use Closure;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class SearchMovie implements Pipe
{
    /**
     * @param MovieTransfer $payload
     * @param Closure $next
     *
     * @return mixed
     */
    public function handle($payload, Closure $next): mixed
    {
        if ($payload->tmdbId) {
            return $next($payload);
        }

        $results = Http::tmdb()
            ->get('search/movie', [
                'language' => app()->getLocale(),
                'query' => $payload->name,
                'year' => $payload->year,
            ])
            ->throw()
            ->collect('results');

        if ($results->isEmpty()) {
            return $next($payload);
        }

        if ($results->count() === 1) {
            $this->mapToDto($results->first(), $payload);

            return $next($payload);
        }

        $match = collect([
            $results
                ->filter(fn (array $result): bool => $result['original_title'] === $payload->name && $result['title'] === $payload->nam),
            $results
                ->filter(fn (array $result): bool => $result['original_title'] === $payload->name),
            $results
                ->filter(fn (array $result): bool => $result['title'] === $payload->name),
            $results
                ->filter(fn (array $result): bool => Carbon::make($result['released_at'])?->year === $payload->year)
                ->filter(fn (array $result): bool => $result['original_title'] === $payload->name && $result['title'] === $payload->nam),
            $results
                ->filter(fn (array $result): bool => Carbon::make($result['released_at'])?->year === $payload->year)
                ->filter(fn (array $result): bool => $result['original_title'] === $payload->name),
            $results
                ->filter(fn (array $result): bool => Carbon::make($result['released_at'])?->year === $payload->year)
                ->filter(fn (array $result): bool => $result['title'] === $payload->name),
        ])->first(fn (Collection $set): bool => $set->count() === 1)->first();

        if (is_array($match)) {
            $this->mapToDto($match, $payload);
        }

        return $next($payload);
    }

    protected function mapToDto(array $result, MovieTransfer $dto): void
    {
        $dto->tmdbId = $result['id'];
        $dto->name ??= $result['title'];
        $dto->description ??= $result['overview'];
        $dto->posterPath ??= $result['poster_path'];
        $dto->backdropPath ??= $result['backdrop_path'];
        $dto->voteAverage ??= $result['vote_average'];
        $dto->releasedAt ??= Carbon::make($result['release_date']);
    }
}
