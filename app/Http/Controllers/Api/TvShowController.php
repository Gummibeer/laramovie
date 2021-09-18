<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\TvShowResource;
use App\Models\TvShow;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class TvShowController
{
    public function index(): JsonResponse
    {
        $tvShows = TvShow::query()
            ->orderBy('name')
            ->get()
            ->groupBy(fn (TvShow $tvShow): string => Str::firstAlpha($tvShow->name))
            ->map(fn (Collection $tvShows) => TvShowResource::collection(
                $tvShows->sortBy(fn (TvShow $tvShow) => Str::ascii($tvShow->name))
            ));

        return response()->json([
            'data' => $tvShows,
        ]);
    }

    public function autocomplete(Request $request): JsonResponse
    {
        $request->validate([
            'query' => ['required', 'string', 'min:3'],
        ]);

        $tvShows = TvShow::search($request->input('query'));

        return TvShowResource::collection($tvShows)
            ->response();
    }
}
