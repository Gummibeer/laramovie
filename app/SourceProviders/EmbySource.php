<?php

namespace App\SourceProviders;

use App\SourceProviders\Contracts\Source;
use App\SourceProviders\Transfers\MovieTransfer;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class EmbySource implements Source
{
    public function __construct(
        protected string $source,
        protected string $baseUrl,
        protected string $apiKey,
        protected string $serverId,
    ) {
    }

    public function movies(): Collection
    {
        return Http::baseUrl($this->baseUrl)
            ->retry(4, 250)
            ->get('emby/Items', [
                'Recursive' => 'true',
                'IncludeItemTypes' => 'Movie',
                'Fields' => 'ProviderIds,MediaStreams',
                'api_key' => $this->apiKey,
            ])
            ->throw()
            ->collect('Items')
            ->filter(fn (array $movie): bool => data_get($movie, 'ProviderIds.Tmdb'))
            ->map(function (array $movie): MovieTransfer {
                $streams = collect($movie['MediaStreams']);

                if ($streams->isEmpty()) {
                    throw new \Exception(sprintf(
                        'Movie without streams: [%s]#%s',
                        $movie['ServerId'],
                        $movie['Id']
                    ));
                }

                return new MovieTransfer(
                    source: $this->source,
                    sourceId: $movie['Id'],
                    tmdbId: $movie['ProviderIds']['Tmdb'],
                    width: $streams->pluck('Width')->filter()->first(),
                    height: $streams->pluck('Height')->filter()->first(),
                );
            });
    }

    public function url(string $id): string
    {
        return sprintf(
            '%s/web/index.html#!/item?id=%s&serverId=%s',
            rtrim($this->baseUrl, '/'),
            $id,
            $this->serverId
        );
    }
}
