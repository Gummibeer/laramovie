<?php

namespace App\SourceProviders;

use App\SourceProviders\Contracts\Source;
use Illuminate\Support\Manager;
use Illuminate\Support\Str;
use InvalidArgumentException;

/**
 * @method \App\SourceProviders\Contracts\Source driver($driver = null)
 */
class SourceManager extends Manager
{
    public function getDefaultDriver(): ?string
    {
        return config('sources.default');
    }

    protected function createDriver($driver): Source
    {
        if (isset($this->customCreators[$driver])) {
            return $this->callCustomCreator($driver);
        }

        if ($this->config->has("sources.providers.{$driver}")) {
            $config = $this->config->get("sources.providers.{$driver}");
            $method = 'create'.Str::studly($config['driver']).'Driver';

            if (method_exists($this, $method)) {
                return $this->$method($driver, $config);
            }
        }

        throw new InvalidArgumentException("Driver [$driver] not supported.");
    }

    protected function createEmbyDriver(string $name, array $config): EmbySource
    {
        return new EmbySource(
            $name,
            $config['base_url'],
            $config['api_key'],
            $config['server_id'],
        );
    }
}
