<div class="max-w-lg w-full lg:max-w-lg">
    <label for="search" class="sr-only">Search</label>
    <div class="relative" x-data="search">

        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <!-- Heroicon name: solid/search -->
                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                </svg>
            </div>
            <input
                id="search"
                name="search"
                class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                placeholder="Search"
                type="search"
                x-model.debounce="query"
            />
        </div>

        <div
            class="origin-top-right absolute right-0 mt-4 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none"
            role="menu"
            aria-orientation="vertical"
            aria-labelledby="menu-button"
            tabindex="-1"
            x-show="(movies && movies.length) || (tvShows && tvShows.length) || (people && people.length)"
            x-transition
        >
            <div class="py-1 flex space-x-4" role="none">

                <ul role="list" class="divide-y divide-gray-200" x-bind:hidden="!(movies && movies.length)">
                    <template x-for="movie in movies" hidden>
                    <li>
                        <a
                            x-bind:href="route('app.movie.show', movie.id)"
                            x-bind:title="movie.name"
                            class="px-4 py-2 flex flex-row items-center space-x-3 hover:bg-gray-100 group"
                        >
                            <div class="w-8 flex-none aspect-w-2 aspect-h-3">
                                <img
                                    x-bind:src="movie.poster"
                                    x-bind:alt="movie.name"
                                    loading="lazy"
                                    class="rounded w-full h-full object-center object-cover overflow-hidden"
                                />
                            </div>
                            <div class="flex-shrink">
                                <p class="text-sm font-medium text-gray-900 group-hover:text-indigo-500 truncate max-w-xs" x-text="movie.name"></p>
                                <p class="text-sm text-gray-500" x-text="movie.released_in"></p>
                            </div>
                        </a>
                    </li>
                    </template>
                </ul>

                <ul role="list" class="divide-y divide-gray-200" x-bind:hidden="!(tvShows && tvShows.length)">
                    <template x-for="tvShow in tvShows" hidden>
                    <li>
                        <a
                            x-bind:href="route('app.tvshow.show', tvShow.id)"
                            x-bind:title="tvShow.name"
                            class="px-4 py-2 flex flex-row items-center space-x-3 hover:bg-gray-100 group"
                        >
                            <div class="w-8 flex-none aspect-w-2 aspect-h-3">
                                <img
                                    x-bind:src="tvShow.poster"
                                    x-bind:alt="tvShow.name"
                                    loading="lazy"
                                    class="rounded w-full h-full object-center object-cover overflow-hidden"
                                />
                            </div>
                            <div class="flex-shrink">
                                <p class="text-sm font-medium text-gray-900 group-hover:text-indigo-500 truncate max-w-xs" x-text="tvShow.name"></p>
                                <p class="text-sm text-gray-500" x-text="tvShow.released_in"></p>
                            </div>
                        </a>
                    </li>
                    </template>
                </ul>

                <ul role="list" class="divide-y divide-gray-200" x-bind:hidden="!(people && people.length)">
                    <template x-for="person in people" hidden>
                    <li>
                        <a
                            x-bind:href="route('app.person.show', person.id)"
                            x-bind:title="person.name"
                            class="px-4 py-2 flex flex-row items-center space-x-3 hover:bg-gray-100 group"
                        >
                            <div class="w-8 flex-none aspect-w-2 aspect-h-3">
                                <img
                                    x-bind:src="person.poster"
                                    x-bind:alt="person.name"
                                    loading="lazy"
                                    class="rounded w-full h-full object-center object-cover overflow-hidden"
                                />
                            </div>
                            <div class="flex-shrink">
                                <p class="text-sm font-medium text-gray-900 group-hover:text-indigo-500 truncate max-w-xs" x-text="person.name"></p>
                            </div>
                        </a>
                    </li>
                    </template>
                </ul>

            </div>
        </div>

    </div>
</div>
