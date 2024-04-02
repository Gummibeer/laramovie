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
    <div class="relative h-2 w-full bg-gray-700">
        <div
            @class([
                'h-full',
                'bg-yellow-700' => $percentage < 100,
                'bg-green-700' => $percentage >= 100,
            ])
            style="width: {{ $percentage }}%"
        ></div>
    </div>
</article>
