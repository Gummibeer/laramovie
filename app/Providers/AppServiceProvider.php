<?php

namespace App\Providers;

use Carbon\Carbon;
use finfo;
use Illuminate\Contracts\Filesystem\Filesystem as FilesystemContract;
use Illuminate\Contracts\Routing\ResponseFactory as ResponseFactoryContract;
use Illuminate\Http\Client\Factory;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Illuminate\Support\Stringable;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

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

        Stringable::macro('firstAlpha', function (): Stringable {
            /** @var Stringable $this */
            return $this
                ->ascii()
                ->trim()
                ->lower()
                ->substr(0, 1)
                ->replaceMatches('/[^a-z]/', '#')
                ->whenEmpty(fn () => Str::of('#'));
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

        Response::macro('storageStream', function (FilesystemContract $disk, string $filepath, array $headers = []) {
            /** @var ResponseFactoryContract $this */
            abort_unless($disk->exists($filepath), SymfonyResponse::HTTP_NOT_FOUND);

            $mimeType = $disk->mimeType($filepath);
            $fullSize = $disk->size($filepath);
            $size = $fullSize;
            $lastModifiedAt = Carbon::createFromTimestamp($disk->lastModified($filepath));

            $stream = $disk->readStream($filepath);

            $headers = array_merge([
                'Content-Type' => $mimeType,
                'Content-Length' => $size,
                'Content-Disposition' => sprintf('attachment; filename="%s"', basename($filepath)),
                'Last-Modified' => $lastModifiedAt->toRfc7231String(),
            ], $headers);

            return $this->stream(
                fn () => fpassthru($stream),
                SymfonyResponse::HTTP_OK,
                $headers
            );
        });
    }
}
