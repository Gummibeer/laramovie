@props(['movie'])

<article class="flex flex-col shadow-lg bg-white rounded">
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
    <aside class="px-2 pb-2 flex-grow text-gray-400">
        <div class="flex justify-between">
            <span>{{ $movie->runtime()?->forHumans(short: true) }}</span>
            <time datetime="{{ $movie->release_date?->toIso8601ZuluString() }}">
                {{ $movie->release_date?->format('Y') ?? '-' }}
            </time>
        </div>
        @if($movie->vote_average)
        <div>★ {{ number_format($movie->vote_average, 1, ',') }} / 10</div>
        @endif
    </aside>
    <x-movie.resolution :movie="$movie"/>
</article>
