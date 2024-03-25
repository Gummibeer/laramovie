<nav class="bg-white shadow sticky top-0 z-10">
    <div class="max-w-7xl mx-auto px-2 sm:px-4 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex px-2 lg:px-0">
                <div class="hidden lg:flex lg:space-x-8">
                    <div class="relative" x-data="{open: false}" @click.away="open = false">
                        <a
                            href="{{ route('app.movie.index') }}"
                            @class([
                                'inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium h-full',
                                'border-indigo-500 text-gray-900' => request()->is('app/movie', 'app/movie/*'),
                                'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' => !request()->is('app/movie', 'app/movie/*'),
                            ])
                            @mouseenter="open = true"
                        >
                            <span>Movies</span>
                            <aside class="text-gray-400 ml-1">({{ \App\Models\OwnedMovie::query()->distinct('movie_id')->count() }})</aside>
                        </a>

                        <div class="absolute z-10 mt-3 px-2 w-screen max-w-xs sm:px-0" x-show="open" x-transition>
                            <div class="rounded-lg shadow-lg ring-1 ring-black ring-opacity-5 overflow-hidden">
                                <div class="relative bg-white p-2 sm:p-4 grid gap-2 sm:gap-4 grid-cols-1">
                                    <a href="{{ route('app.movie.popular') }}" class="-m-2 p-2 flex items-start rounded-lg hover:bg-gray-50">
                                        <strong class="text-base font-medium text-gray-900">
                                            Popular
                                        </strong>
                                    </a>
                                    <a href="{{ route('app.movie.trending') }}" class="-m-2 p-2 flex items-start rounded-lg hover:bg-gray-50">
                                        <strong class="text-base font-medium text-gray-900">
                                            Trending
                                        </strong>
                                    </a>
                                    <a href="{{ route('app.movie.toprated') }}" class="-m-2 p-2 flex items-start rounded-lg hover:bg-gray-50">
                                        <strong class="text-base font-medium text-gray-900">
                                            Top-Rated
                                        </strong>
                                    </a>
                                    <a href="{{ route('app.movie.upcoming') }}" class="-m-2 p-2 flex items-start rounded-lg hover:bg-gray-50">
                                        <strong class="text-base font-medium text-gray-900">
                                            Upcoming
                                        </strong>
                                    </a>
                                    <a href="{{ route('app.movie.recommend') }}" class="-m-2 p-2 flex items-start rounded-lg hover:bg-gray-50">
                                        <strong class="text-base font-medium text-gray-900">
                                            Recommended
                                        </strong>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <a
                        href="{{ route('app.person.index') }}"
                        @class([
                            'inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium',
                            'border-indigo-500 text-gray-900' => request()->is('app/person', 'app/person/*'),
                            'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' => !request()->is('app/person', 'app/person/*'),
                        ])
                    >
                        <span>People</span>
                        <aside class="text-gray-400 ml-1">({{ \Astrotomic\Tmdb\Models\Person::count() }})</aside>
                    </a>
                </div>
            </div>
            <div class="flex-1 flex items-center justify-center px-2 lg:ml-6 lg:justify-end">
                <x-layouts.app.search/>
            </div>

            <div class="flex items-center lg:hidden">
                <!-- Mobile menu button -->
                <button type="button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500" aria-controls="mobile-menu" aria-expanded="false">
                    <span class="sr-only">Open main menu</span>
                    <!--
                      Icon when menu is closed.

                      Heroicon name: outline/menu

                      Menu open: "hidden", Menu closed: "block"
                    -->
                    <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <!--
                      Icon when menu is open.

                      Heroicon name: outline/x

                      Menu open: "block", Menu closed: "hidden"
                    -->
                    <svg class="hidden h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>


            <div class="hidden lg:ml-4 lg:flex lg:items-center">
                <div class="relative flex-shrink-0" x-data="{open:false}" @click.away="open = false">
                    <button
                        type="button"
                        class="bg-white rounded-full flex text-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        id="user-menu-button"
                        aria-expanded="false"
                        aria-haspopup="true"
                        title="{{ auth()->user()->name }}"
                        @click.prevent="open = true"
                    >
                        <span class="sr-only">Open user menu</span>
                        <span class="h-8 inline-flex items-center">
                            <img class="h-8 w-8 rounded-full" src="{{ \Illuminate\Support\Facades\File::base64(auth()->user()->avatar) }}"/>
                        </span>
                    </button>

                    <div
                        class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none"
                        role="menu"
                        aria-orientation="vertical"
                        aria-labelledby="user-menu-button"
                        tabindex="-1"
                        x-show="open"
                        x-transition
                    >
                        <form action="{{ route('auth.signout') }}" method="POST" role="menuitem">
                            @csrf
                            <button type="submit" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 w-full text-left" tabindex="-1">
                                Sign out
                            </button>
                        </form>
                    </div>
                </div>
            </div>


        </div>
    </div>

    <!-- Mobile menu, show/hide based on menu state. -->
    <div class="lg:hidden" id="mobile-menu">
        <div class="pt-2 pb-3 space-y-1">
            <a
                href="{{ route('app.movie.index') }}"
                @class([
                    'flex pl-3 pr-4 py-2 border-l-4 text-base font-medium',
                    'border-indigo-500 text-gray-900' => request()->is('app/movie', 'app/movie/*'),
                    'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' => !request()->is('app/movie', 'app/movie/*'),
                ])
            >
                <span>Movies</span>
                <aside class="text-gray-400 ml-1">({{ \App\Models\OwnedMovie::query()->distinct('movie_id')->count() }})</aside>
            </a>
            <a
                href="{{ route('app.person.index') }}"
                @class([
                    'flex pl-3 pr-4 py-2 border-l-4 text-base font-medium',
                    'border-indigo-500 text-gray-900' => request()->is('app/person', 'app/person/*'),
                    'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' => !request()->is('app/person', 'app/person/*'),
                ])
            >
                <span>People</span>
                <aside class="text-gray-400 ml-1">({{ \Astrotomic\Tmdb\Models\Person::count() }})</aside>
            </a>
        </div>
    </div>
</nav>
