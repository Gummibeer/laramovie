@props(['groups'])

<div x-data='@json(['activeGroup' => $groups->keys()->first()])'>
    <section class="container mx-auto pr-8 pl-8 py-4 sm:py-8 space-y-4 sm:space-y-8">
        @foreach($groups as $group => $movies)
            <div
                id="group-{{ $group }}"
                class="grid gap-4 sm:gap-8 grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6"
                x-intersect.enter="activeGroup = '{{ $group }}'"
            >
                <h2 class="col-span-full text-2xl font-bold uppercase">{{ $group }}</h2>

                @foreach($movies as $movie)
                    <x-movie.preview :movie="$movie"/>
                @endforeach
            </div>
        @endforeach
    </section>

    <nav class="fixed left-0 bottom-0 z-20 bg-gray-900">
        <ul class="grid grid-cols-1 py-1">
            @foreach($groups->keys() as $group)
                <li>
                    <a
                        href="#group-{{ $group }}"
                        class="px-1.5 py-0.5 text-white hover:bg-indigo-500 uppercase text-center inline-block w-full font-mono leading-none"
                        x-bind:class="{ 'bg-white text-gray-900': activeGroup == '{{ $group }}' }"
                    >{{ $group }}</a>
                </li>
            @endforeach
        </ul>
    </nav>
</div>
