<?php

namespace App\Providers;

use App\Flysystem\CachedAdapter;
use App\Flysystem\GoogleDriveAdapter;
use Carbon\CarbonInterval;
use Google\Service\Drive;
use Illuminate\Contracts\Container\Container as ContainerContract;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use League\Flysystem\Config;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemInterface;

class StorageServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        Storage::extend('gdrive', static function (ContainerContract $app, array $config): FilesystemInterface {
            $adapter = new GoogleDriveAdapter(
                $app->make(Drive::class),
                $config['root'],
                [
                    'useDisplayPaths' => $config['useDisplayPaths'] ?? false,
                ]
            );

            $cached = new CachedAdapter(
                $adapter,
                cache()->store(),
                CarbonInterval::minutes(30)
            );

            return new Filesystem(
                $cached,
                new Config([
                    'disable_asserts' => true,
                ])
            );
        });
    }
}
