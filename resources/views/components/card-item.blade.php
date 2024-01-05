@props(['tag'])
<li
    class="w-full border-b-2 border-neutral-100 border-opacity-100 p-4 dark:border-opacity-50">
    <span class="font-semibold">{{$tag}}:</span>
    {{$slot}}
</li>