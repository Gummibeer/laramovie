<?php

namespace App\Workflows\Pipes;

use App\Workflows\Contracts\Pipe;
use App\Workflows\Dtos\Transfer;
use Closure;
use Illuminate\Support\Facades\Storage;

class CheckTmdbidFile implements Pipe
{
    /**
     * @param Transfer $payload
     * @param Closure $next
     *
     * @return mixed
     */
    public function handle($payload, Closure $next): mixed
    {
        if ($payload->tmdbId) {
            return $next($payload);
        }

        $path = sprintf(
            '%s/tmdbid.txt',
            trim($payload->directory, '/')
        );

        if (Storage::disk($payload->disk)->exists($path)) {
            $payload->tmdbId = (int) trim(Storage::disk($payload->disk)->get($path)) ?: null;
        }

        return $next($payload);
    }
}
