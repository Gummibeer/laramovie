<?php

namespace App\Workflows\Pipes;

use App\Exceptions\TmdbIdMissingException;
use App\Workflows\Contracts\Pipe;
use App\Workflows\Dtos\PersonTransfer;
use Closure;
use Illuminate\Support\Facades\Http;

class GetTmdbPerson implements Pipe
{
    /**
     * @param PersonTransfer $payload
     * @param Closure $next
     *
     * @return mixed
     */
    public function handle($payload, Closure $next): mixed
    {
        if (! $payload->tmdbId) {
            throw new TmdbIdMissingException();
        }

        $result = Http::tmdb()
            ->get(sprintf('person/%d', $payload->tmdbId), [
                'language' => app()->getLocale(),
            ])
            ->throw()
            ->json();

        $payload->imdbId = $result['imdb_id'];
        $payload->name = trim($result['name']);
        $payload->description = $result['biography'];
        $payload->posterPath = $result['profile_path'];

        return $next($payload);
    }
}
