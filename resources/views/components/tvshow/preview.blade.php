@props(['tvShow'])

<article class="flex flex-col shadow-lg bg-white rounded pb-2">
    <a href="{{ route('app.tvshow.show', $tvShow) }}" title="{{ $tvShow->name }}">
        <x-poster :model="$tvShow" class="rounded-t"/>
    </a>
    <a href="{{ route('app.tvshow.show', $tvShow) }}" class="truncate p-2 font-bold" title="{{ $tvShow->name }}">
        {{ $tvShow->name }}
    </a>
    <aside class="px-2 text-gray-400">
        <div class="flex justify-between">
            <time datetime="{{ $tvShow->released_at?->toIso8601ZuluString() }}">
                {{ $tvShow->released_at?->format('Y') }}
            </time>
        </div>
        <div>★ {{ number_format($tvShow->vote_average, 1, ',') }} / 10</div>
    </aside>
</article>