<?php

namespace App\Providers;

use Google\Service\Drive;
use Illuminate\Contracts\Container\Container as ContainerContract;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemInterface;
use Masbug\Flysystem\GoogleDriveAdapter;

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

            return new Filesystem($adapter);
        });
    }
}
