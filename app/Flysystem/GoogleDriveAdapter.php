<?php

namespace App\Flysystem;

use ReflectionClass;

class GoogleDriveAdapter extends \Masbug\Flysystem\GoogleDriveAdapter
{
    protected array $sanitizeChars = [
        '/', '\\', '?', '%', '*', ':', '|', '"', '<', '>',
        '\x00', '\x01', '\x02', '\x03', '\x04', '\x05', '\x06', '\x07', '\x08', '\x09', '\x0A', '\x0B', '\x0C', '\x0D', '\x0E', '\x0F',
        '\x10', '\x11', '\x12', '\x13', '\x14', '\x15', '\x16', '\x17', '\x18', '\x19', '\x1A', '\x1B', '\x1C', '\x1D', '\x1E', '\x1F',
        '\x7F', '\xA0', '\xAD',
    ];

    protected function sanitizeFilename($filename): string
    {
        return str_replace(
            $this->sanitizeChars,
            '_',
            $filename
        );
    }

    public function readStreamA($path): array
    {
        if ($this->useDisplayPaths()) {
            $path = $this->toVirtualPath($path, false, true);
        }

        $token = $this->service->getClient()->getAccessToken()['access_token'];

        $url = parse_url(
            sprintf(
                'https://www.googleapis.com/drive/v3/files/%s?alt=media',
                $path
            )
        );

        $stream = stream_socket_client('ssl://'.$url['host'].':443');
        stream_set_timeout($stream, 300);
        fwrite($stream, "GET {$url['path']}?{$url['query']} HTTP/1.1\r\n");
        fwrite($stream, "Host: {$url['host']}\r\n");
        fwrite($stream, "Authorization: Bearer {$token}\r\n");
        fwrite($stream, "Connection: Close\r\n");
        fwrite($stream, "\r\n");

        return [
            'stream' => $stream,
        ];
    }

    protected function useDisplayPaths(): bool
    {
        $class = new ReflectionClass($this);
        $parent = $class->getParentClass();
        $property = $parent->getProperty('useDisplayPaths');
        $property->setAccessible(true);

        return $property->getValue($this);
    }
}
