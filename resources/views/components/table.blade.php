<table
    {!! $attributes->merge(['class' => 'border-collapse table-auto w-full text-sm mt-1 mb-1']) !!}
>
    <thead>
        {{ $thead }}
    </thead>
    <tbody class="bg-white dark:bg-slate-800">
        {{ $tbody }}
    </tbody>
</table>