<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use SocialiteProviders\Trakt\Provider;

class TraktController
{
    public function callback(): RedirectResponse
    {
        $socialite = $this->socialite()->user();

        $user = User::query()->updateOrCreate(
            ['slug' => $socialite->user['ids']['slug']],
            [
                'name' => $socialite->name,
                'nickname' => $socialite->nickname,
                'trakt_token' => $socialite->token,
                'avatar' => $socialite->user['images']['avatar']['full'] ?? null,
            ]
        );

        $user->syncWatchedMovies();

        Auth::login($user, true);

        return redirect()->intended(
            route('app.movie.index')
        );
    }

    public function redirect(): RedirectResponse
    {
        return $this->socialite()
            ->redirectUrl(route('auth.trakt.callback'))
            ->redirect();
    }

    protected function socialite(): Provider
    {
        return Socialite::driver('trakt');
    }
}
