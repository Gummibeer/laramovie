<?php

namespace App\Workflows\Pipes;

use App\Workflows\Contracts\Pipe;
use App\Workflows\Dtos\Transfer;
use Closure;

class ParseFoldername implements Pipe
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

        if (preg_match('/^(.+)\s+\((\d+)\)$/', $payload->directory, $hits)) {
            $payload->name ??= trim($hits[1]);
            $payload->year ??= $hits[2];
        } else {
            $payload->name = trim($payload->directory);
        }

        return $next($payload);
    }
}
