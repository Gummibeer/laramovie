<x-layouts.app>

<section class="container mx-auto px-4 sm:px-0 py-4 sm:py-8">
    <div>
        <h2 class="text-2xl font-bold mb-4">Collections</h2>

        <div class="mb-4">
            <strong>Completed Collections</strong>
            <x-progressbar :current="$completed->count()" :total="$collections->count()" class="rounded"/>
        </div>

        <div class="mb-4">
            <strong>Collected Movies</strong>
            <x-progressbar :current="$movieCount['owned']" :total="$movieCount['total']" class="rounded"/>
        </div>

        <div class="grid gap-4 sm:gap-8 grid-cols-1 md:grid-cols-2 xl:grid-cols-3">
            @foreach($collections as $collection)
                <x-collection.preview :collection="$collection"/>
            @endforeach
        </div>
    </div>
</section>

<section class="container mx-auto px-4 sm:px-0 py-4 sm:py-8">
    <div>
        <h2 class="text-2xl font-bold mb-4">Collectable Movies</h2>
        <div class="grid gap-4 sm:gap-8 grid-cols-1 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-6">
            @foreach($movies as $movie)
                <x-movie.preview :movie="$movie"/>
            @endforeach
        </div>
    </div>
</section>

</x-layouts.app>
