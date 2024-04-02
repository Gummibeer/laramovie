<?php

namespace App\View\Components\Collection;

use App\Models\OwnedMovie;
use Astrotomic\Tmdb\Models\Collection;
use Astrotomic\Tmdb\Models\Movie;
use Carbon\CarbonInterval;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\Component;

class Preview extends Component
{
    public function __construct(protected Collection $collection)
    {
    }

    public function render(): View
    {
        $percentage = Cache::remember(
            key: "collection.{$this->collection->id}.percentage",
            ttl: CarbonInterval::day(),
            callback: fn () => Movie::query()
                ->whereIn('id', OwnedMovie::query()->distinct()->pluck('movie_id'))
                ->where('collection_id', $this->collection->id)
                ->count()
                /
                Movie::query()
                    ->where('collection_id', $this->collection->id)
                    ->count()
                * 100
        );

        return view('components.collection.preview', [
            'collection' => $this->collection,
            'percentage' => $percentage,
        ]);
    }
}
