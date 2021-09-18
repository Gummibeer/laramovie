<?php

namespace App\Http\Resources;

use App\Models\Movie;
use Illuminate\Http\Resources\Json\JsonResource;

class MovieResource extends JsonResource
{
    /** @var Movie */
    public $resource;

    public function toArray($request): array
    {
        return [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
            'poster' => $this->resource->poster(),
            'released_in' => $this->resource->released_at?->format('Y'),
            'readable' => [
                'runtime' => $this->resource->runtime()?->forHumans(short: true),
                'vote_average' => number_format($this->resource->vote_average, 1, ','),
            ],
            'has_been_watched' => auth()->user()->hasWatched($this->resource),
        ];
    }
}
