<?php

namespace App\Http\Resources;

use App\Models\TvShow;
use Illuminate\Http\Resources\Json\JsonResource;

class TvShowResource extends JsonResource
{
    /** @var TvShow */
    public $resource;

    public function toArray($request): array
    {
        return [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
            'poster' => $this->resource->poster(),
            'released_in' => $this->resource->released_at?->format('Y'),
            'readable' => [
                'vote_average' => number_format($this->resource->vote_average, 1, ','),
            ],
        ];
    }
}
