<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Staudenmeir\EloquentJsonRelations\HasJsonRelationships;
use Staudenmeir\EloquentJsonRelations\Relations\BelongsToJson;

/**
 * App\Models\User.
 *
 * @property int $id
 * @property string $slug
 * @property string $nickname
 * @property string $name
 * @property string|null $trakt_token
 * @property string|null $avatar
 * @property string|null $remember_token
 *
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable;
    use Authorizable;
    use HasJsonRelationships;

    public $timestamps = false;
    protected $hidden = [
        'remember_token',
        'trakt_token',
    ];
    protected $casts = [
        'watched_movie_ids' => 'array',
    ];

    public function watched_movies(): BelongsToJson
    {
        return $this->belongsToJson(Movie::class, 'watched_movie_ids');
    }

    public function watch(Movie $movie): bool
    {
        if ($this->trakt_token === null) {
            return false;
        }

        $type = match ($movie::class) {
            Movie::class => 'movies',
            TvShow::class => 'shows',
            Season::class => 'seasons',
            Episode::class => 'episodes',
        };

        $response = Http::trakt()
            ->withToken($this->trakt_token)
            ->post('/sync/history', [
                $type => [[
                    'watched_at' => now()->toIso8601ZuluString(),
                    'ids' => [
                        'tmdb' => $movie->id,
                    ],
                ]],
            ])
            ->json();

        return $response['added'][$type] === 1
            && empty(array_filter($response['not_found']))
            && $this->watched_movies()->attach($movie)->save();
    }

    public function hasWatched(Movie $movie): bool
    {
        return $this->watched_movies->contains($movie);
    }

    public function syncWatchedMovies(): bool
    {
        return $this->watched_movies()->attach(
            $this->trakt()->get('/sync/watched/movies')
                ->collect()
                ->pluck('movie.ids.tmdb')
        )->save();
    }

    public function trakt(): PendingRequest
    {
        return Http::trakt()->withToken($this->trakt_token);
    }
}
