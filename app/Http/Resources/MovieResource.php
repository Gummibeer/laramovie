<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MovieResource extends JsonResource
{
    /** @var \Astrotomic\Tmdb\Models\Movie */
    public $resource;

    public function toArray($request): array
    {
        return [
            'id' => $this->resource->id,
            'name' => $this->resource->title,
            'poster' => $this->resource->poster()->url(),
            'released_in' => $this->resource->release_date?->format('Y'),
            'readable' => [
                'runtime' => $this->resource->runtime()?->forHumans(short: true),
                'vote_average' => number_format($this->resource->vote_average, 1, ','),
            ],
        ];
    }
}
