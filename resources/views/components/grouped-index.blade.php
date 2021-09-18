@props(['url'])

<div x-data="groupedIndex('{{ url($url) }}')">

    <section class="container mx-auto pr-8 pl-8 py-4 sm:py-8 space-y-4 sm:space-y-8">

        <template x-for="[group, items] of Object.entries(groupedItems)" hidden>
            <div
                class="grid gap-4 sm:gap-8 grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6"
                x-bind:id="'group-'+group"
                x-intersect.enter="activeGroup = group"
            >
                <h2 class="col-span-full text-2xl font-bold uppercase" x-text="group"></h2>

                <template x-for="item in items" hidden>

                    {{ $slot }}

                </template>

            </div>
        </template>

    </section>

    <nav class="fixed left-0 bottom-0 z-20 bg-gray-900">
        <ul class="grid grid-cols-1 py-1">
            <template x-for="group in Object.keys(groupedItems)" hidden>
                <li>
                    <a
                        x-bind:href="'#group-'+group"
                        x-text="group"
                        x-bind:class="{ 'bg-white text-gray-900': activeGroup == group }"
                        class="px-1.5 py-0.5 text-white hover:bg-indigo-500 uppercase text-center inline-block w-full font-mono leading-none"
                    ></a>
                </li>
            </template>
        </ul>
    </nav>

</div>