<?php

namespace App\Providers;

use App\Actions\LoadMovieFromGdrive;
use App\Actions\LoadMovieFromTmdb;
use finfo;
use Illuminate\Http\Client\Factory;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Illuminate\Support\Stringable;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(LoadMovieFromGdrive::class);
        $this->app->singleton(LoadMovieFromTmdb::class);
    }

    public function boot(): void
    {
        Http::macro('tmdb', function (): PendingRequest {
            /** @var Factory $this */

            return $this
                ->baseUrl('https://api.themoviedb.org/3')
                ->acceptJson()
                ->withToken(config('services.tmdb.token'))
                ->retry(4, 250);
        });

        Http::macro('trakt', function (): PendingRequest {
            /** @var Factory $this */

            return $this
                ->baseUrl('https://api.trakt.tv')
                ->acceptJson()
                ->asJson()
                ->withHeaders([
                    'trakt-api-key' => config('services.trakt.client_id'),
                ])
                ->retry(4, 250);
        });

        Str::macro('firstAlpha', function (string $string): string {
            return Str::of($string)->firstAlpha();
        });

        Stringable::macro('firstAlpha', function () {
            /** @var Stringable $this */
            return $this
                ->ascii()
                ->substr(0, 1)
                ->lower()
                ->replaceMatches('/[^a-z]/', '#');
        });

        File::macro('json', function (string $path): array {
            return json_decode(File::get($path), true);
        });

        File::macro('collect', function (string $path): Collection {
            return collect(File::json($path));
        });

        File::macro('base64', function (string $path): string {
            $data = file_get_contents($path);
            $type = (new finfo(FILEINFO_MIME))->buffer($data);

            return 'data:'.$type.';base64,'.base64_encode($data);
        });
    }
}
