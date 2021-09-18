<x-layouts.html class="flex flex-col min-h-screen bg-gray-100">

    <x-layouts.app.header/>

    <main {{ $attributes->merge(['class' => 'relative']) }}>
        {{ $slot }}
    </main>

    <footer></footer>

</x-layouts.html>