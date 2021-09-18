<x-layouts.app>

    <section class="container mx-auto px-4 sm:px-0 py-4 sm:py-8">
        <div class="grid gap-8 grid-cols-1 md:grid-cols-3 lg:grid-cols-4 rounded">

            <div>
                <x-poster :model="$person" size="780" class="rounded shadow-lg"/>
            </div>

            <div class="md:col-span-2 lg:col-span-3">
                <div class="space-y-4 bg-white rounded p-4 md:p-8 shadow-lg">
                    <h1 class="text-3xl font-bold">{{ $person->name }}</h1>
                    <p>{{ $person->biography }}</p>
                    <ul class="flex flex-row space-x-4">
                        <li>
                            <a href="{{ $person->tmdb_link() }}" target="_blank" class="inline-block bg-green-500 text-white rounded px-4 py-1.5">
                                TMDB
                            </a>
                        </li>
                        @if($person->imdb_link())
                        <li>
                            <a href="{{ $person->imdb_link() }}" target="_blank" class="inline-block bg-yellow-500 text-white rounded px-4 py-1.5">
                                IMDB
                            </a>
                        </li>
                        @endif
                    </ul>
                </div>
            </div>

            @if($person->movies->isNotEmpty())
            <div class="col-span-full">
                <h2 class="text-2xl font-bold mb-4">Movies</h2>
                <div class="grid gap-4 sm:gap-8 grid-cols-1 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-6">
                @foreach($person->movies as $movie)
                    <x-movie.preview :movie="$movie"/>
                @endforeach
                </div>
            </div>
            @endif

            @if($person->tv_shows->isNotEmpty())
            <div class="col-span-full">
                <h2 class="text-2xl font-bold mb-4">TV Shows</h2>
                <div class="grid gap-4 sm:gap-8 grid-cols-1 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-6">
                @foreach($person->tv_shows as $tvShow)
                    <x-tvshow.preview :tv-show="$tvShow"/>
                @endforeach
                </div>
            </div>
            @endif

        </div>
    </section>

</x-layouts.app>