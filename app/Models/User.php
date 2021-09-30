<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

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
 *
 * @property array $watched_movie_ids
 */
class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable;
    use Authorizable;

    public $timestamps = false;
    protected $hidden = [
        'remember_token',
        'trakt_token',
    ];

    public function trakt(): PendingRequest
    {
        return Http::trakt()->withToken($this->trakt_token);
    }
}
