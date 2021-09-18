<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\PersonResource;
use App\Models\Person;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class PersonController
{
    public function index(): JsonResponse
    {
        $people = Person::query()
            ->orderBy('name')
            ->get()
            ->groupBy(fn (Person $person): string => Str::firstAlpha($person->name))
            ->map(fn (Collection $people) => PersonResource::collection(
                $people->sortBy(fn (Person $person) => Str::ascii($person->name))
            ));

        return response()->json([
            'data' => $people,
        ]);
    }

    public function autocomplete(Request $request): JsonResponse
    {
        $request->validate([
            'query' => ['required', 'string', 'min:3'],
        ]);

        $people = Person::search($request->input('query'));

        return PersonResource::collection($people)
            ->response();
    }
}
