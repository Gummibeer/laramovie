<x-layouts.app>

    <section class="container mx-auto px-4 sm:px-0 py-4 sm:py-8">
        <div class="grid gap-8 md:grid-cols-3 lg:grid-cols-4">

            <div>
                <x-poster :model="$movie" size="780" class="rounded shadow-lg"/>
            </div>

            <div class="md:col-span-2 lg:col-span-3">
                <div class="space-y-4 bg-white rounded p-4 md:p-8 shadow-lg">
                    <h1 class="text-3xl font-bold">{{ $movie->name }}</h1>
                    <ul class="flex flex-row space-x-4">
                        @foreach($movie->genres as $genre)
                            <li>{{ $genre }}</li>
                        @endforeach
                    </ul>
                    <ul class="flex flex-row space-x-4 text-gray-400">
                        <li>{{ $movie->runtime()?->forHumans(short: true) }}</li>
                        <li>{{ $movie->release_date?->format('Y') }}</li>
                        <li>★ {{ number_format($movie->vote_average, 1, ',') }} / 10</li>
                    </ul>
                    <p>{{ $movie->overview }}</p>
                    <ul class="flex flex-row space-x-4">
                        <li>
                            <a href="https://www.themoviedb.org/movie/{{ $movie->id }}" target="_blank" class="inline-block bg-green-500 text-white rounded px-4 py-1.5">
                                TMDB
                            </a>
                        </li>
                        @if($movie->imdb_id)
                        <li>
                            <a href="https://www.imdb.com/title/{{ $movie->imdb_id }}" target="_blank" class="inline-block bg-yellow-500 text-white rounded px-4 py-1.5">
                                IMDB
                            </a>
                        </li>
                        @endif
                        @unless(false && auth()->user()->hasWatched($movie))
                        <li>
                            <form
                                action="{{ route('api.movie.watch', $movie) }}"
                                method="PATCH"
                                x-data
                                @submit.prevent="fetch($el.getAttribute('action'), {
                                    method: $el.getAttribute('method'),
                                    headers: {Accept: 'application/json'}
                                }).then(() => $el.classList.add('hidden'))"
                            >
                                <button type="submit" class="inline-block bg-red-400 text-white rounded px-4 py-1.5">
                                    watch
                                </button>
                            </form>
                        </li>
                        @endunless
                        @foreach($movie->videos() as $video)
                            <li>
                                <a href="{{ $video['link'] }}" target="_blank" class="inline-block bg-green-500 text-white rounded px-4 py-1.5">
                                    ⤓ {{ $video['video_format'] }} ({{ number_format(\App\Converter::from($video['size'])->toGiB(), 2, ',') }}GB)
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            @if($movie->recommendations(18)->isNotEmpty())
                <div class="col-span-full">
                    <h2 class="text-2xl font-bold mb-4">Similar Movies</h2>
                    <div class="grid gap-4 sm:gap-8 grid-cols-1 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-6">
                        @foreach($movie->recommendations(18) as $recommendation)
                            <x-movie.preview :movie="$recommendation"/>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($movie->cast->isNotEmpty())
            <div class="col-span-full">
                <h2 class="text-2xl font-bold mb-4">Cast</h2>
                <div class="grid gap-4 sm:gap-8 grid-cols-1 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-6">
                @foreach($movie->cast as $person)
                    <x-person.preview :person="$person"/>
                @endforeach
                </div>
            </div>
            @endif

            @if($movie->crew->isNotEmpty())
            <div class="col-span-full">
                <h2 class="text-2xl font-bold mb-4">Crew</h2>
                <div class="grid gap-4 sm:gap-8 grid-cols-1 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-6">
                @foreach($movie->crew as $person)
                    <x-person.preview :person="$person"/>
                @endforeach
                </div>
            </div>
            @endif

        </div>
    </section>

</x-layouts.app>
