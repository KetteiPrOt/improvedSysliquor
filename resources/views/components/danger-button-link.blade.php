<button 
    {{ $attributes->merge(['type' => 'submit', 'class' => '
        text-red-400 underline
    ']) }}
>
    {{ $slot }}
</button>
