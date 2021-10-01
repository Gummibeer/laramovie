<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\MovieResource;
use App\Models\OwnedMovie;
use Astrotomic\Tmdb\Models\Movie;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MovieController
{
    public function index(): JsonResponse
    {
        $movies = Movie::query()
            ->whereExists(static function (Builder $query) {
                $query
                    ->select(DB::raw(1))
                    ->from(OwnedMovie::table())
                    ->whereColumn(OwnedMovie::qualifiedColumn('movie_id'), Movie::qualifiedColumn('id'));
            })
            ->get()
            ->groupBy(fn (Movie $movie): string => Str::firstAlpha($movie->title))
            ->sortBy(fn ($_, string $key) => $key)
            ->map(fn (Collection $movies) => MovieResource::collection(
                $movies->sortBy(fn (Movie $movie) => Str::ascii($movie->title))
            ));

        return response()->json([
            'data' => $movies,
        ]);
    }
}
