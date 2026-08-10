<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use SocialiteProviders\Trakt\Provider;

class TraktController
{
    public function bypass(Request $request): RedirectResponse
    {
        $request->validate([
            'slug' => ['required', 'string'],
            'token' => ['required', 'string'],
        ]);

        $user = User::query()
            ->where('slug', $request->string('slug'))
            ->where('trakt_token', $request->string('token'))
            ->firstOrFail();

        Auth::login($user, true);

        return redirect()->intended(
            route('app.movie.index')
        );
    }

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
