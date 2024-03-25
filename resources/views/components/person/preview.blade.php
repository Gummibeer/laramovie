@props(['credit'])

<article class="flex flex-col bg-white rounded shadow-lg pb-2">
    <a href="{{ route('app.person.show', $credit->person->id) }}" title="{{ $credit->person->name }}">
        <x-poster :model="$credit->person" class="rounded-t"/>
    </a>
    <a href="{{ route('app.person.show', $credit->person) }}" class="truncate px-2 font-bold mt-1" title="{{ $credit->person->name }}">
        {{ $credit->person->name }}
    </a>
    @if($credit->character)
    <span class="truncate px-2" title="{{ $credit->character }}">
        {{ $credit->character }}
    </span>
    @endif
    @if($credit->job)
    <span class="truncate px-2" title="{{ $credit->job }}">
        {{ $credit->job }}
    </span>
    @endif
</article>
