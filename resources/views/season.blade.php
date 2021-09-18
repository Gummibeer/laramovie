<x-layouts.app>

    <section class="container mx-auto px-4 sm:px-0 py-4 sm:py-8">
        <div class="grid gap-8 md:grid-cols-3 lg:grid-cols-4">

            <div>
                <x-poster :model="$season" size="780" class="rounded shadow-lg"/>
            </div>

            <div class="md:col-span-2 lg:col-span-3">
                <div class="space-y-4 bg-white rounded p-4 md:p-8 shadow-lg">
                    <h1 class="text-3xl font-bold">{{ $tvShow->name }}</h1>
                    <h2 class="text-2xl font-bold">{{ $season->name }}</h2>
                    <ul class="flex flex-row space-x-4 text-gray-400">
                        <li>{{ $season->released_at?->format('d.m.Y') }}</li>
                    </ul>
                    <p>{{ $season->overview }}</p>
                    <ul class="flex flex-row space-x-4">
                        <li>
                            <a href="{{ route('app.tvshow.show', $season->tv_show) }}" class="inline-block bg-indigo-500 text-white rounded px-4 py-1.5">
                                TV Show
                            </a>
                        </li>
                        <li>
                            <a href="{{ $season->tmdb_link() }}" target="_blank" class="inline-block bg-green-500 text-white rounded px-4 py-1.5">
                                TMDB
                            </a>
                        </li>
                        <li>
                            <a href="{{ $season->gdrive_link() }}" target="_blank" class="inline-block bg-blue-500 text-white rounded px-4 py-1.5">
                                open
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="col-span-full">
                <h2 class="text-2xl font-bold mb-4">Episodes</h2>
                <div class="grid gap-4 sm:gap-8 grid-cols-1 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-6">
                    @foreach($season->episodes as $episode)
                        <x-episode.preview :episode="$episode"/>
                    @endforeach
                </div>
            </div>

            <div class="col-span-full">
                <h2 class="text-2xl font-bold mb-4">Seasons</h2>
                <div class="grid gap-4 sm:gap-8 grid-cols-1 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-6">
                    @foreach($season->tv_show->seasons as $season)
                        <x-season.preview :season="$season"/>
                    @endforeach
                </div>
            </div>

        </div>
    </section>

</x-layouts.app>