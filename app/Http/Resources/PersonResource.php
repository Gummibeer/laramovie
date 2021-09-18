<?php

namespace App\Http\Resources;

use App\Models\Person;
use Illuminate\Http\Resources\Json\JsonResource;

class PersonResource extends JsonResource
{
    /** @var Person */
    public $resource;

    public function toArray($request): array
    {
        return [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
            'poster' => $this->resource->poster(),
        ];
    }
}
