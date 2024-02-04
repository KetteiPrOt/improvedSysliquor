@props(['movement'])

<x-kardex.table.triple-cell>
    <x-slot name="first">
        {{$movement->amount}}
    </x-slot>
    <x-slot name="second">
        {{$movement->unitary_price}}
    </x-slot>
    <x-slot name="third">
        {{round($movement->amount * $movement->unitary_price, 2, PHP_ROUND_HALF_UP)}}
    </x-slot>
</x-kardex.table.triple-cell>