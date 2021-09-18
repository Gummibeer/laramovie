<x-layouts.app>

<section class="container mx-auto px-4 sm:px-0 py-4 sm:py-8">
    <div>
        <h2 class="text-2xl font-bold mb-4">Unmapped Movies</h2>

        <div class="flex flex-col">
            <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                    <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    #
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Name
                                </th>
                                <th scope="col" class="relative px-6 py-3">
                                    <span class="sr-only">Actions</span>
                                </th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($folders as $folder)
                            <tr
                                @class([
                                    'bg-white' => $loop->odd,
                                    'bg-gray-50' => $loop->even,
                                ])
                                x-data="{gdrive_id:'{{ $folder['id'] }}',tmdb_id:null}"
                            >
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <a
                                        href="{{ $folder['link'] }}"
                                        target="_blank"
                                        class="font-medium text-gray-900 hover:text-indigo-500"
                                    >
                                        {{ $folder['id'] }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <a
                                        href="https://www.themoviedb.org/search/movie?query={{ urlencode($folder['movie']['name'].' y:'.$folder['movie']['year']) }}"
                                        target="_blank"
                                        class="text-gray-500 hover:text-indigo-500"
                                    >
                                        {{ $folder['name'] }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <form
                                        action="{{ route('api.movie.assign') }}"
                                        method="POST"
                                        @submit.prevent="fetch($el.getAttribute('action'), {
                                            method: $el.getAttribute('method'),
                                            headers: {
                                                'Accept': 'application/json',
                                                'Content-Type': 'application/json',
                                            },
                                            body: JSON.stringify({gdrive_id,tmdb_id})
                                        }).then(() => $root.remove())"
                                    >
                                        <input
                                            type="text"
                                            inputmode="number"
                                            name="tmdb_id"
                                            class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                            placeholder="12345"
                                            x-model="tmdb_id"
                                        />
                                    </form>
                                </td>
                            </tr>
                            @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>




    </div>
</section>

</x-layouts.app>