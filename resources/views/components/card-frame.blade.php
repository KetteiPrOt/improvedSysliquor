@props(['display' => 'block', 'maxwidth' => 'max-w-sm'])

<div
    {!! $attributes->merge([
        'class' => "
            $display
            w-full $maxwidth rounded-lg bg-white
            shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)]
        "
    ])!!}
>
    <ul class="w-full">
        {{$slot}}
    </ul>
</div>