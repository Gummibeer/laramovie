<?php

namespace App\Http\Controllers\Api;

use App\Actions\LoadMovieFromGdrive;
use App\Http\Resources\MovieResource;
use App\Models\Movie;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class MovieController
{
    public function index(): JsonResponse
    {
        $movies = Movie::query()
            ->orderBy('name')
            ->get()
            ->groupBy(fn (Movie $movie): string => Str::firstAlpha($movie->name))
            ->map(fn (Collection $movies) => MovieResource::collection(
                $movies->sortBy(fn (Movie $movie) => Str::ascii($movie->name))
            ));

        return response()->json([
            'data' => $movies,
        ]);
    }

    public function autocomplete(Request $request): JsonResponse
    {
        $request->validate([
            'query' => ['required', 'string', 'min:3'],
        ]);

        $movies = Movie::search($request->input('query'));

        return MovieResource::collection($movies)
            ->response();
    }

    public function watch(Movie $movie): JsonResponse
    {
        abort_unless(auth()->user()->watch($movie), Response::HTTP_INTERNAL_SERVER_ERROR);

        return response()->json(null, Response::HTTP_CREATED);
    }

    public function assign(Request $request, Drive $drive): JsonResponse
    {
        $request->validate([
            'gdrive_id' => ['required', 'string'],
            'tmdb_id' => ['required', 'integer', 'gt:0'],
        ]);

        $file = Arr::first($drive->files->listFiles([
            'fields' => 'files(id)',
            'spaces' => 'drive',
            'q' => sprintf(
                'trashed = false and "%s" in parents and name = "tmdbid.txt"',
                $request->input('gdrive_id')
            ),
        ])->getFiles());

        abort_if($file instanceof DriveFile, Response::HTTP_CONFLICT);

        $df = new DriveFile();
        $df->setName('tmdbid.txt');
        $df->setMimeType('text/text');
        $df->setParents([$request->input('gdrive_id')]);

        $file = $drive->files->create($df, [
            'fields' => 'id, name',
        ]);

        abort_unless(
            Storage::disk('gdrive')->put(
                $file->getId(),
                $request->input('tmdb_id')
            ),
            Response::HTTP_INTERNAL_SERVER_ERROR
        );

        app()->terminating(fn () => app()->call(LoadMovieFromGdrive::class, [
            'folderId' => $request->input('gdrive_id'),
        ]));

        return response()->json(null, Response::HTTP_CREATED);
    }
}
