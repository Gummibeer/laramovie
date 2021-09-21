<?php

namespace App\Flysystem;

use Closure;
use DateInterval;
use League\Flysystem\AdapterInterface;
use League\Flysystem\Config;
use Psr\SimpleCache\CacheInterface;

class CachedAdapter implements AdapterInterface
{
    public function __construct(
        protected AdapterInterface $adapter,
        protected CacheInterface $cache,
        protected int | DateInterval $ttl
    ) {
    }

    public function read($path): array|false
    {
        return $this->adapter->read($path);
    }

    public function readStream($path): array|false
    {
        return $this->adapter->readStream($path);
    }

    public function update($path, $contents, Config $config): array|false
    {
        return $this->adapter->update($path, $contents, $config);
    }

    public function updateStream($path, $resource, Config $config): array|false
    {
        return $this->adapter->updateStream($path, $resource, $config);
    }

    public function write($path, $contents, Config $config): array|false
    {
        return $this->adapter->write($path, $contents, $config);
    }

    public function writeStream($path, $resource, Config $config): array|false
    {
        return $this->adapter->writeStream($path, $resource, $config);
    }

    public function delete($path): bool
    {
        return $this->adapter->delete($path);
    }

    public function deleteDir($dirname): bool
    {
        return $this->adapter->delete($dirname);
    }

    public function rename($path, $newpath): bool
    {
        return $this->adapter->rename($path, $newpath);
    }

    public function createDir($dirname, Config $config): array|false
    {
        return $this->adapter->createDir($dirname, $config);
    }

    public function copy($path, $newpath): bool
    {
        return $this->adapter->copy($path, $newpath);
    }

    public function has($path): bool
    {
        return $this->adapter->has($path);
    }

    public function listContents($directory = '', $recursive = false)
    {
        return $this->remember(
            $this->cacheKey(__FUNCTION__, $directory, compact('recursive')),
            fn () => $this->adapter->listContents($directory, $recursive)
        );
    }

    public function getSize($path): array|false
    {
        return $this->remember(
            $this->cacheKey(__FUNCTION__, $path),
            fn () => $this->adapter->getSize($path)
        );
    }

    public function getMetadata($path): array|false
    {
        return $this->remember(
            $this->cacheKey(__FUNCTION__, $path),
            fn () => $this->adapter->getMetadata($path)
        );
    }

    public function getMimetype($path): array|false
    {
        return $this->remember(
            $this->cacheKey(__FUNCTION__, $path),
            fn () => $this->adapter->getMimetype($path)
        );
    }

    public function getTimestamp($path): array|false
    {
        return $this->remember(
            $this->cacheKey(__FUNCTION__, $path),
            fn () => $this->adapter->getTimestamp($path)
        );
    }

    public function getVisibility($path): array|false
    {
        return $this->adapter->getVisibility($path);
    }

    public function setVisibility($path, $visibility): array|false
    {
        return $this->adapter->setVisibility($path, $visibility);
    }

    protected function remember(string $key, Closure $callback): mixed
    {
        if ($this->cache->has($key)) {
            return $this->cache->get($key);
        }

        $result = $callback();

        $this->cache->set($key, $result, $this->ttl);

        return $result;
    }

    protected function cacheKey(string $method, string $path, array $additional = []): string
    {
        ksort($additional);

        return sprintf(
            '%s::%s(%s, %s)',
            $this->adapter::class,
            $method,
            method_exists($this->adapter, 'applyPathPrefix')
                ? $this->adapter->applyPathPrefix($path)
                : $path,
            json_encode($additional),
        );
    }
}
