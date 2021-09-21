<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\File;
use Throwable;

class Handler extends ExceptionHandler
{
    protected $dontReport = [];

    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        $this->reportable(static function (TmdbIdMissingException $e): bool {
            if (! $e->getDisk() || ! $e->getDirectory()) {
                return false;
            }

            $filepath = storage_path(sprintf(
                'app/movies/%s/%s.json',
                $e->getDisk(),
                hash('md5', $e->getDirectory())
            ));

            File::ensureDirectoryExists(dirname($filepath));
            File::put($filepath, json_encode([
                'disk' => $e->getDisk(),
                'directory' => $e->getDirectory(),
            ]));

            return false;
        });
    }
}
