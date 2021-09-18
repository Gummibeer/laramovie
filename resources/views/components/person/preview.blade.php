@props(['person'])

<article class="flex flex-col bg-white rounded shadow-lg pb-2">
    <a href="{{ route('app.person.show', $person->id) }}" title="{{ $person->name }}">
        <x-poster :model="$person" class="rounded-t"/>
    </a>
    <a href="{{ route('app.person.show', $person) }}" class="truncate px-2 font-bold mt-1" title="{{ $person->name }}">
        {{ $person->name }}
    </a>
    @if($person->role)
    <span class="truncate px-2" title="{{ $person->role->character }}">
        {{ $person->role->character }}
    </span>
    @endif
</article>