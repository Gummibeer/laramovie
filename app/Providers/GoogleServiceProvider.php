<?php

namespace App\Providers;

use Google\Client;
use Google\Service\Drive;
use Google_Client;
use Google_Service_Drive;
use Illuminate\Contracts\Config\Repository as ConfigContract;
use Illuminate\Contracts\Container\Container as ContainerContract;
use Illuminate\Support\ServiceProvider;

class GoogleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(Client::class, static function (ContainerContract $app): Client {
            /** @var ConfigContract $config */
            $config = $app->make('config');

            $client = new Client();
            $client->setClientId($config->get('services.google.client_id'));
            $client->setClientSecret($config->get('services.google.client_secret'));
            $client->refreshToken($config->get('services.google.refresh_token'));

            return $client;
        });
        $this->app->alias(Client::class, Google_Client::class);

        $this->app->bind(Drive::class, static function (ContainerContract $app): Drive {
            return new Drive($app->make(Client::class));
        });
        $this->app->alias(Drive::class, Google_Service_Drive::class);
    }

    public function boot(): void
    {
    }
}
