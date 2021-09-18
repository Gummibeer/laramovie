<?php

namespace App\Models\Concerns;

use App\Models\Model;
use Fuse\Fuse;
use Illuminate\Support\Collection;

trait Searchable
{
    public static function search(string|array $query, int $limit = 4): Collection
    {
        $fuse = new Fuse(
            static::query()
                ->select(array_merge(['id'], static::searchableAttributes()))
                ->get()
                ->toArray(),
            [
                'keys' => static::searchableAttributes(),
                'isCaseSensitive' => true,
                'shouldSort' => true,
                'ignoreLocation' => true,
                'minMatchCharLength' => 1,
                'includeScore' => true,
                'threshold' => 0.4,
            ]
        );

        $hits = collect($fuse->search($query, [
            'limit' => $limit,
        ]))->keyBy('item.id');

        return static::query()
            ->whereIn('id', $hits->pluck('item.id'))
            ->get()
            ->sortBy(fn (Model $model) => $hits[$model->id]['score']);
    }

    protected static function searchableAttributes(): array
    {
        return ['name'];
    }
}
