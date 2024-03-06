@props(['name', 'column', 'order'])

<a
    href="{{
        request()->fullUrlWithQuery([
            'column' => $name,
            'order' => $order == 'asc' ? 'desc' : 'asc'
        ])
    }}"

    {!! $attributes !!}
>
    {{$slot}}
    @if($name == $column)
        @if($order == 'asc')
            <x-icons.order.ascending
                class="w-5 h-5"
            />
        @else
            <x-icons.order.descending
                class="w-5 h-5"
            />
        @endif
    @else
        <x-icons.order
            class="w-5 h-5"
        />
    @endif
</a>
