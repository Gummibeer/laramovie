<x-layouts.app>

<section class="container mx-auto px-4 sm:px-0 py-4 sm:py-8">
    <div>
        <h2 class="text-2xl font-bold mb-4">Popular Movies</h2>
        <div class="grid gap-4 sm:gap-8 grid-cols-1 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-6">
            @foreach($movies as $movie)
                <x-movie.preview :movie="$movie"/>
            @endforeach
        </div>
    </div>
</section>

</x-layouts.app>
