@props(['movement'])

<x-kardex.table.triple-cell>
    <x-slot name="first">
        {{$movement->amount}}
    </x-slot>
    <x-slot name="second">
        {{'$' . $movement->unitary_price}}
    </x-slot>
    <x-slot name="third">
        {{'$' . $movement->total_price}}
    </x-slot>
</x-kardex.table.triple-cell>