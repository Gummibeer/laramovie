@props(['movie'])

<article
    @class([
        'flex flex-col shadow-lg bg-white rounded pb-2',
        'opacity-50 hover:opacity-100' => auth()->user()->hasWatched($movie),
    ])
>
    @if($movie->exists)
    <a href="{{ route('app.movie.show', $movie) }}" title="{{ $movie->name }}">
        <x-poster :model="$movie" class="rounded-t"/>
    </a>
    <a href="{{ route('app.movie.show', $movie) }}" title="{{ $movie->name }}" class="truncate p-2 font-bold">
        {{ $movie->name }}
    </a>
    @else
    <x-poster :model="$movie" class="rounded-t"/>
    <span class="truncate p-2 font-bold">{{ $movie->name }}</span>
    @endif
    <aside class="px-2 text-gray-400">
        <div class="flex justify-between">
            <span>{{ $movie->runtime()?->forHumans(short: true) }}</span>
            <time datetime="{{ $movie->released_at->toIso8601ZuluString() }}">
                {{ $movie->released_at->format('Y') }}
            </time>
        </div>
        @if($movie->vote_average)
        <div>★ {{ number_format($movie->vote_average, 1, ',') }} / 10</div>
        @endif
    </aside>
</article>