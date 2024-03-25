@props(['movie'])

<article
    @class([
        'flex flex-col shadow-lg bg-white rounded pb-2',
        //'opacity-50 hover:opacity-100' => auth()->user()->hasWatched($movie),
    ])
>
    @if($movie->exists)
    <a href="{{ route('app.movie.show', $movie) }}" title="{{ $movie->title }}">
        <x-poster :image="$movie->poster()" class="rounded-t"/>
    </a>
    <a href="{{ route('app.movie.show', $movie) }}" title="{{ $movie->title }}" class="truncate p-2 font-bold">
        {{ $movie->title }}
    </a>
    @else
    <x-poster :image="$movie->poster()" class="rounded-t"/>
    <span class="truncate p-2 font-bold">{{ $movie->title }}</span>
    @endif
    <aside class="px-2 text-gray-400">
        <div class="flex justify-between">
            <span>{{ $movie->runtime()?->forHumans(short: true) }}</span>
            <time datetime="{{ $movie->release_date->toIso8601ZuluString() }}">
                {{ $movie->release_date->format('Y') }}
            </time>
        </div>
        @if($movie->vote_average)
        <div>★ {{ number_format($movie->vote_average, 1, ',') }} / 10</div>
        @endif
    </aside>
</article>
