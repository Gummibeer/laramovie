<x-layouts.app>

    <x-grouped-index :url="route('api.movie.index')">

        {{-- <x-movie.preview> --}}
        <article
            class="flex flex-col shadow-lg bg-white rounded pb-2"
            x-bind:class="{ 'opacity-50 hover:opacity-100': item.has_been_watched}"
        >
            <a x-bind:href="route('app.movie.show', item.id)" x-bind:title="item.name">
                <x-poster src="" alt="" x-bind:src="item.poster" x-bind:alt="item.name" class="rounded-t"/>
            </a>
            <a
                x-bind:href="route('app.movie.show', item.id)"
                x-bind:title="item.name"
                class="truncate p-2 font-bold"
                x-text="item.name"
            ></a>
            <aside class="px-2 text-gray-400">
                <div class="flex justify-between">
                    <span x-text="item.readable.runtime"></span>
                    <time x-text="item.released_in"></time>
                </div>
                <div>★ <span x-text="item.readable.vote_average"></span> / 10</div>
            </aside>
        </article>
        {{-- </x-movie.preview> --}}

    </x-grouped-index>

</x-layouts.app>