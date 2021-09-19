<?php

namespace App\Workflows\Pipes;

use App\Exceptions\TmdbIdMissingException;
use App\Workflows\Contracts\Pipe;
use App\Workflows\Dtos\MovieTransfer;
use Carbon\Carbon;
use Closure;
use Illuminate\Support\Facades\Http;

class GetTmdbMovie implements Pipe
{
    /**
     * @param MovieTransfer $payload
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
            ->get(sprintf('movie/%d', $payload->tmdbId), [
                'language' => app()->getLocale(),
                'append_to_response' => 'credits',
            ])
            ->throw()
            ->json();

        $payload->imdbId = $result['imdb_id'];
        $payload->name = trim($result['title']);
        $payload->description = $result['overview'];
        $payload->posterPath = $result['poster_path'];
        $payload->backdropPath = $result['backdrop_path'];
        $payload->voteAverage = $result['vote_average'];
        $payload->runtime = $result['runtime'];
        $payload->releasedAt = Carbon::make($result['release_date']);
        $payload->genres = array_column($result['genres'], 'name');
        $payload->cast = array_column($result['credits']['cast'], 'id');
        $payload->crew = array_column($result['credits']['crew'], 'id');

        return $next($payload);
    }
}
