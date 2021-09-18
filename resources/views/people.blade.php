<x-layouts.app>

    <x-grouped-index :url="route('api.person.index')">

        {{-- <x-person.preview> --}}
        <article class="flex flex-col bg-white rounded shadow-lg pb-2">
            <a x-bind:href="route('app.person.show', item.id)" x-bind:title="item.name">
                <x-poster src="" alt="" x-bind:src="item.poster" x-bind:alt="item.name" class="rounded-t"/>
            </a>
            <a
                x-bind:href="route('app.person.show', item.id)"
                x-bind:title="item.name"
                x-text="item.name"
                class="truncate px-2 font-bold mt-1"
            ></a>
        </article>
        {{-- </x-person.preview> --}}

    </x-grouped-index>

</x-layouts.app>