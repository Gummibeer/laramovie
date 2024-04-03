<article class="bg-center bg-cover bg-no-repeat rounded overflow-hidden" style="background-image: url({{ $collection->backdrop()->url() }});">
    <div @class([
        'p-4 text-white',
        'bg-black bg-opacity-50' => $percentage < 100,
        'bg-green-900 bg-opacity-75' => $percentage >= 100,
    ])>
        <a href="https://www.themoviedb.org/collection/{{ $collection->id }}" class="block text-xl font-bold truncate">
            {{ $collection->name }}
        </a>
    </div>
    <x-progressbar :current="$helper->ownedMovieCount()" :total="$helper->movieCount()" class="rounded-b"/>
</article>
