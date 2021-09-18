@props(['episode'])

<article class="flex flex-col shadow-lg bg-white rounded pb-2">
    <x-still :model="$episode" class="rounded-t"/>
    <strong class="truncate p-2 font-bold" title="{{ $episode->name }}">
        {{ $episode->name }}
    </strong>
    <aside class="px-2 text-gray-400">
        <div class="flex justify-between">
            <span>{{ $episode->number }}</span>
            <time datetime="{{ $episode->released_at?->toIso8601ZuluString() }}">
                {{ $episode->released_at?->format('Y') }}
            </time>
        </div>
        <ul class="space-y-0.5">
            @foreach($episode->video_files() as $video)
                <li>
                    <x-video-link :video="$video" class="text-sm text-gray-400 px-1 py-0.5 hover:text-red-500"/>
                </li>
            @endforeach
        </ul>
    </aside>
</article>