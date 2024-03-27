<?php

namespace App\View\Components\Movie;

use App\Models\OwnedMovie;
use Astrotomic\Tmdb\Models\Movie;
use Carbon\CarbonInterval;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\Component;

class Resolution extends Component
{
    public function __construct(protected Movie $movie)
    {
    }

    public function render(): View
    {
        $resolution = Cache::remember(
            key: "movie.{$this->movie->id}.resolution",
            ttl: CarbonInterval::day(),
            callback: fn () => OwnedMovie::query()
                ->where('movie_id', $this->movie->id)
                ->get()
                ->map(fn (OwnedMovie $movie) => $movie->resolution())
                ->unique()
                ->mapWithKeys(fn (string $resolution) => [$resolution => (int) $resolution])
                ->sortDesc()
                ->keys()
                ->first()
        );

        return view('components.movie.resolution', [
            'movie' => $this->movie,
            'resolution' => $resolution,
        ]);
    }
}
