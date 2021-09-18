@props(['season'])

<article class="flex flex-col shadow-lg bg-white rounded pb-2">
    <a href="{{ route('app.tvshow.season.show', ['tvShow' => $season->tv_show, 'season' => $season]) }}" title="{{ $season->name }}">
        <x-poster :model="$season" class="rounded-t"/>
    </a>
    <a href="{{ route('app.tvshow.season.show', ['tvShow' => $season->tv_show, 'season' => $season]) }}" class="truncate p-2 font-bold" title="{{ $season->name }}">
        {{ $season->name }}
    </a>
    <aside class="px-2 text-gray-400">
        <div class="flex justify-between">
            <time datetime="{{ $season->released_at?->toIso8601ZuluString() }}">
                {{ $season->released_at?->format('Y') }}
            </time>
        </div>
    </aside>
</article>