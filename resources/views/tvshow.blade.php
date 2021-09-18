<x-layouts.app>

    <section class="container mx-auto px-4 sm:px-0 py-4 sm:py-8">
        <div class="grid gap-8 md:grid-cols-3 lg:grid-cols-4">

            <div>
                <x-poster :model="$tvShow" size="780" class="rounded shadow-lg"/>
            </div>

            <div class="md:col-span-2 lg:col-span-3">
                <div class="space-y-4 bg-white rounded p-4 md:p-8 shadow-lg">
                    <h1 class="text-3xl font-bold">{{ $tvShow->name }}</h1>
                    <ul class="flex flex-row space-x-4">
                        @foreach($tvShow->genres as $genre)
                            <li>{{ $genre }}</li>
                        @endforeach
                    </ul>
                    <ul class="flex flex-row space-x-4 text-gray-400">
                        <li>{{ $tvShow->released_at?->format('d.m.Y') }}</li>
                        <li>★ {{ number_format($tvShow->vote_average, 1, ',') }}</li>
                    </ul>
                    <p>{{ $tvShow->overview }}</p>
                    <ul class="flex flex-row space-x-4">
                        <li>
                            <a href="{{ $tvShow->tmdb_link() }}" target="_blank" class="inline-block bg-green-500 text-white rounded px-4 py-1.5">
                                TMDB
                            </a>
                        </li>
                        <li>
                            <a href="{{ $tvShow->gdrive_link() }}" target="_blank" class="inline-block bg-blue-500 text-white rounded px-4 py-1.5">
                                open
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="col-span-full">
                <h2 class="text-2xl font-bold mb-4">Seasons</h2>
                <div class="grid gap-4 sm:gap-8 grid-cols-1 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-6">
                    @foreach($tvShow->seasons as $season)
                        <x-season.preview :season="$season"/>
                    @endforeach
                </div>
            </div>

            @if($tvShow->recommendations()->isNotEmpty())
                <div class="col-span-full">
                    <h2 class="text-2xl font-bold mb-4">Similar TV Shows</h2>
                    <div class="grid gap-4 sm:gap-8 grid-cols-1 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-6">
                        @foreach($tvShow->recommendations() as $recommendation)
                            <x-tvshow.preview :tv-show="$recommendation"/>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="col-span-full">
                <h2 class="text-2xl font-bold mb-4">Cast</h2>
                <div class="grid gap-4 sm:gap-8 grid-cols-1 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-6">
                @foreach($tvShow->people as $person)
                    <x-person.preview :person="$person"/>
                @endforeach
                </div>
            </div>

        </div>
    </section>

</x-layouts.app>